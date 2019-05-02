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
    const COUNT_OF_PAGE = 10;

    /**
     * 创建订单
     * @param $itemList
     * @param $buyer_id
     * @return array
     */
    public function createOrder($itemList, $buyer_id)
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
                    'buyer_id' => $buyer_id,
                    'first_img' => $itemList[0]["img"],
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
            $first_product = $itemList[0]["title"];
            if ($count > 1) {
                $first_product = $itemList[0]["title"] . '等' . $count . '件商品';
            }
            //更新order表订单金额
            $status = Db::table('ym_order')
                ->where('order_id', $order_id)
                ->update(['real_price' => $real_price,
                    'first_product' => $first_product]);
            if ($status > 0) {
                return ['order_id' => $order_id,
                    'real_price' => round($real_price, 2),
                    'out_trade_no' => $out_trade_no,
                    'status' => 200, 'msg' => '成功！！'];
            } else {
                return ['status' => 400, 'msg' => '服务器异常！'];
            }
        } catch (PDOException $e) {
        } catch (Exception $e) {
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
    public function aliUpdateOrder($out_trade_no, $trade_no, $trade_status, $pay_amount)
    {
        if ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
            $data['pay_status'] = 1;
            $data['trade_no'] = $trade_no;
            $data['pay_time'] = date('y-m-d H:i:s', time());
            $data['pay_amount'] = $pay_amount;
            $data['pay_type'] = 1;
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
     * 更新订单
     * @param $obj
     * @return string
     * @throws Exception
     * @throws PDOException
     */
    public function weUpdateOrder($obj)
    {

        if ($obj["return_code"] != 'SUCCESS') {
            die($obj->return_msg);
        }
        $data['pay_status'] = 1;
        $data['trade_no'] = $obj["transaction_id"];
        $data['pay_time'] = date('y-m-d H:i:s', time());
        $data['pay_amount'] = number_format($obj["total_fee"] / 100, 2);
        $data['pay_type'] = 2;

        $result = Db::table('ym_order')
            ->where(['out_trade_no' => $obj["out_trade_no"],])
            ->update($data);

        if ($result) {
            $real_price = Db::table('ym_order')->where(['out_trade_no' => $obj["out_trade_no"]])
                ->value('real_price');
            $market_id = Db::table('ym_order')->where(['out_trade_no' => $obj["out_trade_no"]])
                ->value('market_id');
            Db::table('ym_market')
                ->where('market_id', $market_id)
                ->setInc('balance', floatval($real_price));
            return '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
        } else {
            echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
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
                ->where('to_days(create_time) = to_days(now())')
                ->where(['pay_status' => 1, 'market_id' => $market_id])
                ->select()[0]["amount"];

            $yestoday_amount = Db::field('Sum(real_price)AS amount')->table('ym_order')
                ->where('TO_DAYS(NOW()) - TO_DAYS(create_time) = 1')
                ->where(['pay_status' => 1, 'market_id' => $market_id])
                ->select()[0]["amount"];

            $week_amount = Db::field('Sum(real_price) AS amount')->table('ym_order')
                ->where('DATE_SUB(CURDATE(), INTERVAL 6 DAY) <= date(create_time)')
                ->where(['pay_status' => 1, 'market_id' => $market_id])
                ->select()[0]["amount"];

            $month_amount = Db::field('Sum(real_price) AS amount')->table('ym_order')
                ->where('DATE_FORMAT( create_time, \'%Y%m\' ) = DATE_FORMAT( CURDATE() ,\'%Y%m\')')
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
    public function Alipay($order_id, $remark,$address)
    {
        //查询订单信息
        $order_info = Db::table('ym_order')->where(['order_id' => $order_id])->find();
        //获取订单号
        $out_trade_no = $order_info["out_trade_no"];
        //获取支付金额
        $money = $order_info["real_price"];
        //异步回调地址
        $url = config('local_path') . '/pay/alipay/notify';

        $array = alipay('源梦网络', $money, $out_trade_no, $url, $order_info["first_product"]);

        if ($array) {

            Db::table('ym_order')
                ->where('order_id', $order_id)
                ->update(['remark' => $remark,
                    'address' => $address]);

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
     * @param $address
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Exception
     * @throws ModelNotFoundException
     * @throws PDOException
     * @throws WxPayException
     */
    public function Wepay($order_id, $remark,$address)
    {
        //查询订单信息
        $order_info = Db::table('ym_order')->where(['order_id' => $order_id])->find();
        //获取订单号
        $out_trade_no = $order_info["out_trade_no"];
        //获取支付金额
        $money = $order_info["real_price"];

        $result = wepay('TOP校园', $money, $out_trade_no);

        if ($result) {
            //更新order表订单备注

            Db::table('ym_order')
                ->where('order_id', $order_id)
                ->update(['remark' => $remark,
                    'address' => $address]);

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
     * @param $type
     * @param $page
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function selectOrder($market_id, $phone, $token, $type, $page)
    {
        if (!checkToken($token, $phone)) {
            return config('NOT_SUPPORTED');
        }

        switch ($type) {
            case "待付款":
                return $this->selectOrderAndPage($market_id, $page, 0);
            case "已付款":
                return $this->selectOrderAndPage($market_id, $page, 1);
            case "已退款":
                return $this->selectOrderAndPage($market_id, $page, 2);
            case "待处理":
                return $this->selectDispose($market_id, $page, 0);
            case "已处理":
                return $this->selectDispose($market_id, $page, 1);
            default:
                break;
        }

        return ['status' => 200,
            'msg' => '查询失败！！'];
    }

    /**
     * 定单详情查询
     * @param $order_id
     * @param $phone
     * @param $token
     * @param $market_id
     * @return array
     */
    public function selectOrderItem($order_id, $phone, $token, $market_id)
    {
        if (!checkToken($token, $phone)) {
            return config('NOT_SUPPORTED');
        }
        try {
            $orderItemList = Db::table('ym_order_item')
                ->where('order_id', $order_id)
                ->select();
            $order = Db::table('ym_order')
                ->where('order_id', $order_id)
                ->select();
            if ($order[0]["market_id"] != $market_id) {
                return ['status' => 400,
                    'msg' => '参数错误！！'];
            }
            $market_name = Db::table('ym_market')
                ->where('market_id', $order[0]["market_id"])
                ->value('market_name');
            $order[0]["market_name"] = $market_name;
            $order[0]["item"] = $orderItemList;
            return ['status' => 200,
                'msg' => '查询成功！！',
                'order' => $order[0]];
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
        return ['status' => 400,
            'msg' => '查询失败！！'];
    }

    /**
     * 订单列表
     * @param $market_id
     * @param $page
     * @param $type
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    private function selectOrderAndPage($market_id, $page, $type)
    {
        $orderList = Db::table('ym_order')
            ->where('market_id', $market_id)
            ->where('pay_status', $type)
            ->where('dispose', 0)
            ->order('create_time', 'DESC')
            ->page($page, 10)
            ->select();
        $totalPages = ceil(Db::table('ym_order')
                ->where('market_id', $market_id)
                ->where('pay_status', $type)
                ->where('dispose', 0)
                ->count('*') / Order::COUNT_OF_PAGE);
        return ['status' => 200,
            'msg' => '查询成功！！',
            'orderList' => $orderList,
            'totalPages' => $totalPages];
    }

    /**
     * 查询待处理订单
     * @param $market_id
     * @param $page
     * @param $type
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    private function selectDispose($market_id, $page, $type)
    {
        $orderList = Db::table('ym_order')
            ->where('market_id', $market_id)
            ->where('pay_status', 1)
            ->where('dispose', $type)
            ->order('create_time', 'DESC')
            ->page($page, 10)
            ->select();
        $totalPages = ceil(Db::table('ym_order')
                ->where('market_id', $market_id)
                ->where('pay_status', 1)
                ->where('dispose', $type)
                ->count('*') / Order::COUNT_OF_PAGE);
        return ['status' => 200,
            'msg' => '查询成功！！',
            'orderList' => $orderList,
            'totalPages' => $totalPages];

    }

    /**
     * 订单处理
     * @param $phone
     * @param $token
     * @param $order_id
     * @return mixed
     */
    public function dispose($phone, $token, $order_id)
    {
        if (!checkToken($token, $phone)) {
            return config('NOT_SUPPORTED');
        }
        try {
            Db::table('ym_order')
                ->where('order_id', $order_id)
                ->update(['dispose' => 1,
                    'dispose_time'=>date('Y-m-d H:i:s', time())]);
            return ['status' => 200, 'msg' => '处理成功！'];
        } catch (PDOException $e) {
        } catch (Exception $e) {
        }
        return ['status' => 400, 'msg' => '处理失败！', $this->getLastSql()];
    }
}