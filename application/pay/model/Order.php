<?php


namespace app\pay\model;


use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\exception\PDOException;
use think\Model;

class Order extends Model
{
    public function createOrder($itemList, $buyer_id)
    {
        //创建时间
        $create_time = date('y-m-d H:i:s', time());

        //计算价格
        $real_price = 0.00;
        try {
            $out_trade_no = date('YmdHis') . mt_rand(1000000, 9999999);
            //创建订单
            $order_id = Db::table('ym_order')->insertGetId(['out_trade_no' => $out_trade_no,
                'buyer_id' => $buyer_id, 'pay_type' => 1,
                'create_time' => $create_time, 'market_id' => $itemList[0]["market_id"]]);
            foreach ($itemList as $item) {
                //商品单价
                $price = Db::table('ym_product')->where(['id' => $item["id"]])
                    ->value('price');
                //添加order_item表数据
                Db::table('ym_order_item')->insert(['order_id' => $order_id, 'item_count' => $item["count"],
                    'item_name' => $item["title"], 'item_img' => $item["img"], 'item_price' => $price,
                    'item_amount' => $price * $item["count"], 'create_time' => $create_time]);
                $real_price += $price * $item["count"];
            }
            //更新order表订单金额
            $status = Db::table('ym_order')
                ->where('order_id', $order_id)
                ->update(['real_price' => $real_price]);
            if ($status > 0) {
                return ['order_id' => $order_id, 'real_price' => $real_price,
                    'out_trade_no' => $out_trade_no, 'status' => 200, 'msg' => '成功！！'];
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
     * @param $order_id
     * @param $remark
     * @return array
     * @throws Exception
     * @throws PDOException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public function pay($order_id, $remark)
    {
        //查询订单信息
        $order_info = Db::table('ym_order')->where(['order_id' => $order_id])->find();
        //获取订单号
        $out_trade_no = $order_info["out_trade_no"];
        //获取支付金额
        $money = $order_info["real_price"];
        //异步回调地址
        $url = config('local_path') . '/pay/alipay/alipaynotify';

        $array = alipay('源梦网络', $money, $out_trade_no, $url);

        if ($array) {
            //更新order表订单备注
            Db::table('ym_order')
                ->where('order_id', $order_id)
                ->update(['remark' => $remark]);

            return ['alipay_sdk' => $array,
                'status' => '200', 'msg' => '成功'];
        } else {
            return ['alipay_sdk' => [],
                'status' => '400', 'msg' => '失败'];
        }
    }

    /**
     * 获取今、昨、7天、一个月总金额
     * @param $user_id
     * @return array
     */
    public function getAmount($user_id)
    {
        $market_id = Db::table('ym_market')->where('user_id', $user_id)->value('market_id');
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
                ->where('DATE_FORMAT( pay_time, \'%Y%m\' ) = DATE_FORMAT( CURDATE( ) , \'%Y%m\' )')
                ->where(['pay_status' => 1, 'market_id' => $market_id])
                ->select()[0]["amount"];

            $balance = Db::table('ym_market')->where('user_id', $user_id)->value('balance');

            return ['status' => 200, 'msg' => '查询成功！！',
                'today_amount' => $today_amount,
                'yestoday_amount' => $yestoday_amount,
                'week_amount' => $week_amount,
                'month_amount' => $month_amount,
                'balance' => $balance];
        } catch (Exception $exception) {
        }
        return ['status' => 400, 'msg' => '查询失败！！',];
    }

}