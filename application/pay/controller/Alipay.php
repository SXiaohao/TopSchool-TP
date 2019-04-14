<?php


namespace app\pay\controller;


use app\pay\model\Order;
use think\Controller;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\exception\PDOException;
use think\Request;

class Alipay extends Controller
{

    /**
     * @param Request $request
     * @return array|mixed
     * @throws Exception
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     * @throws PDOException
     */
    public function payOrder(Request $request)
    {
        if ($request->isPost()) {
            //获取订单号
            $order_id = $request->param('order_id');
            $remark = $request->param('remark');
            $order = new Order();
            return $order->Alipay($order_id, $remark);
        }
        return config('PARAMS_ERROR');
    }

    /**
     * 支付宝支付回调修改订单状态
     */
    public function alipayNotify()
    {
        //原始订单号
        $out_trade_no = input('out_trade_no');
        //支付宝交易号
        $trade_no = input('trade_no');
        //交易状态
        $trade_status = input('trade_status');

        $pay_amount = input('total_amount');


        if ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {

            $data['trade_status'] = 1;
            $data['pay_status'] = 1;
            $data['trade_no'] = $trade_no;
            $data['pay_time'] = date('y-m-d H:i:s', time());
            $data['pay_amount'] = $pay_amount;
            $result = Db::table('ym_order')->where(['out_trade_no' => $out_trade_no,])->update($data);

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
            $data['trade_status'] = 2;
            $data['pay_status'] = 0;
            $data['trade_no'] = $trade_no;
            $data['pay_time'] = date('y-m-d H:i:s', time());
            $data['pay_amount'] = 0.00;
            Db::table('ym_order')->where(['out_trade_no' => $out_trade_no,])->update($data);
            echo "fail";
        }
    }
}