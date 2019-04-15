<?php


namespace app\pay\model;


use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\exception\PDOException;
use think\Model;
use wxpay\WapPay;
use WxPayException;

class Order extends Model
{
    /**
     * 创建订单
     * @param $itemList
     * @param $buyer_id
     * @param $type
     * @return array
     */
    public function createOrder($itemList, $buyer_id, $type)
    {
        //创建时间
        $create_time = date('y-m-d H:i:s', time());
        //计算价格
        $real_price = 0.00;
        //计算商品数量
        $count = 0;
        try {
            $out_trade_no = date('YmdHis') . mt_rand(1000000, 9999999);
            //创建订单
            $order_id = Db::table('ym_order')
                ->insertGetId(['out_trade_no' => $out_trade_no,
                    'buyer_id' => $buyer_id, 'pay_type' => $type,
                    'first_img' => $itemList[0]["img"],
                    'first_product' => $itemList[0]["title"],
                    'create_time' => $create_time,
                    'market_id' => $itemList[0]["market_id"]]);
            foreach ($itemList as $item) {
                $count += $item["count"];
                //商品单价
                $price = Db::table('ym_product')
                    ->where(['id' => $item["id"]])
                    ->value('price');
                //添加order_item表数据
                Db::table('ym_order_item')
                    ->insert(['order_id' => $order_id, 'item_count' => $item["count"],
                        'item_name' => $item["title"], 'item_img' => $item["img"],
                        'item_price' => $price, 'item_amount' => $price * $item["count"],
                        'create_time' => $create_time]);
                $real_price += $price * $item["count"];
            }
            //更新order表订单金额
            $status = Db::table('ym_order')
                ->where('order_id', $order_id)
                ->update(['real_price' => $real_price,
                    'count' => $count]);
            if ($status > 0) {
                $address = Db::table('ym_user_address')
                    ->where('user_id', $buyer_id)
                    ->value('address');
                return ['order_id' => $order_id,
                    'real_price' => round($real_price, 2),
                    'out_trade_no' => $out_trade_no,
                    'address' => $address,
                    'status' => 200, 'msg' => '成功！！'];
            } else {
                return ['status' => 400, 'msg' => '服务器异常！'];
            }
        } catch (PDOException $e) {
            var_dump($this->getLastSql());
        } catch (Exception $e) {
            var_dump($this->getLastSql());
        }
        return ['status' => 400, 'msg' => '参数错误！'];
    }

    /**
     * 更新订单
     * @param $out_trade_no
     * @param $trade_no
     * @param $trade_status
     * @param $pay_amount
     * @throws Exception
     * @throws PDOException
     */
    public function updateOrder($out_trade_no, $trade_no, $trade_status, $pay_amount)
    {
        if ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
            $data['trade_status'] = 1;
            $data['pay_status'] = 1;
            $data['trade_no'] = $trade_no;
            $data['pay_time'] = date('y-m-d H:i:s', time());
            $data['pay_amount'] = $pay_amount;
            $result = Db::table('ym_order')
                ->where(['out_trade_no' => $out_trade_no,])
                ->update($data);

            if ($result) {
                $real_price = Db::table('ym_order')->where(['out_trade_no' => $out_trade_no])
                    ->value('real_price');
                $market_id = Db::table('ym_order')->where(['out_trade_no' => $out_trade_no])
                    ->value('market_id');
                Db::table('ym_market')
                    ->where('market_id', $market_id)
                    ->setInc('balance', floatval($real_price));
                echo 'success';
            } else {
                echo 'fail';
            }
        } else {
            echo 'fail';
        }
    }

    /**
     * 获取今、昨、7天、一个月总金额
     * @param $market_id
     * @return array
     */
    public function getAmount($market_id)
    {
        try {
            $today_amount = Db::field('Sum(real_price) AS amount')->table('ym_order')
                ->where('to_days(pay_time) = to_days(now())')
                ->where(['pay_status' => 1, 'market_id' => $market_id])
                ->select()[0]["amount"];

            $yestoday_amount = Db::field('Sum(real_price) AS amount')->table('ym_order')
                ->where('TO_DAYS( NOW( ) ) - TO_DAYS( pay_time) = 1')
                ->where(['pay_status' => 1, 'market_id' => $market_id])
                ->select()[0]["amount"];

            $week_amount = Db::field('Sum(real_price) AS amount')->table('ym_order')
                ->where('YEARWEEK(date_format(pay_time,\'%Y-%m-%d\')) = YEARWEEK(now())')
                ->where(['pay_status' => 1, 'market_id' => $market_id])
                ->select()[0]["amount"];

            $month_amount = Db::field('Sum(real_price) AS amount')->table('ym_order')
                ->where('DATE_FORMAT( pay_time, \'%Y%m\' ) = DATE_FORMAT( CURDATE() ,\'%Y%m\')')
                ->where(['pay_status' => 1, 'market_id' => $market_id])
                ->select()[0]["amount"];

            $balance = Db::table('ym_market')->where('market_id', $market_id)
                ->value('balance');
            return ['status' => 200, 'msg' => '查询成功！！',
                'today_amount' => floatval($today_amount),
                'yestoday_amount' => floatval($yestoday_amount),
                'week_amount' => floatval($week_amount),
                'month_amount' => floatval($month_amount),
                'balance' => floatval($balance)];
        } catch (Exception $exception) {
        }
        return ['status' => 400, 'msg' => '查询失败！！'];
    }

    /**
     * 阿里支付
     * @param $order_id
     * @param $remark
     * @param $address
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Exception
     * @throws ModelNotFoundException
     * @throws PDOException
     */
    public function Alipay($order_id, $remark, $address)
    {
        //查询订单信息
        $order_info = Db::table('ym_order')->where(['order_id' => $order_id])->find();
        //获取订单号
        $out_trade_no = $order_info["out_trade_no"];
        //获取支付金额
        $money = $order_info["real_price"];
        //异步回调地址
        $url = config('local_path') . '/pay/alipay/notify';

        $array = alipay('源梦网络', $money, $out_trade_no, $url);

        if ($array) {
            //更新order表订单备注
            Db::table('ym_order')
                ->where('order_id', $order_id)
                ->update(['remark' => $remark,
                    '$address' => $address]);

            return ['alipay_sdk' => $array,
                'status' => '200', 'msg' => '成功'];
        } else {
            return ['alipay_sdk' => [],
                'status' => '400', 'msg' => '失败'];
        }
    }

    /**
     * 微信支付
     * @param $order_id
     * @param $remark
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Exception
     * @throws ModelNotFoundException
     * @throws PDOException
     * @throws WxPayException
     */
    public function Wepay($order_id, $remark)
    {
        //查询订单信息
        $order_info = Db::table('ym_order')->where(['order_id' => $order_id])->find();
        //获取订单号
        $out_trade_no = $order_info["out_trade_no"];
        //获取支付金额
        $money = $order_info["real_price"];
        $params = [
            'body' => '源梦网络',
            'out_trade_no' => $out_trade_no,
            'total_fee' => $money,
        ];
        $result = WapPay::getPayUrl($params);
        if ($result) {
            //更新order表订单备注
            Db::table('ym_order')
                ->where('order_id', $order_id)
                ->update(['remark' => $remark]);

            return ['wepay_sdk' => $result,
                'status' => '200', 'msg' => '成功'];
        } else {
            return ['wepay_sdk' => [],
                'status' => '400', 'msg' => '失败'];
        }
    }

    /**
     * 定单查询
     * @param $market_id
     * @param $phone
     * @param $token
     * @return array
     */
    public function selectOrder($market_id, $phone, $token)
    {
        if (!checkToken($token, $phone)) {
            return config('NOT_SUPPORTED');
        }
        try {
            $orderList = Db::table('ym_order')
                ->where('market_id', $market_id)
                ->where('pay_status', 1)
                ->select();
            return ['status' => 200,
                'msg' => '查询成功！！',
                'orderList' => $orderList];
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
        return ['status' => 200,
            'msg' => '查询失败！！'];
    }

    /**
     * 定单详情查询
     * @param $order_id
     * @param $phone
     * @param $token
     * @return array
     */
    public function selectOrderItem($order_id, $phone, $token)
    {
        if (!checkToken($token, $phone)) {
            return config('NOT_SUPPORTED');
        }
        try {
            $orderItemList = Db::table('ym_order_item')
                ->where('order_id', $order_id)
                ->select();
            return ['status' => 200,
                'msg' => '查询成功！！',
                'orderItemList' => $orderItemList];
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
        return ['status' => 200,
            'msg' => '查询失败！！'];
    }
}