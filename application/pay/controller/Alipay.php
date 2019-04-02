<?php


namespace app\pay\controller;


use app\pay\model\Order;
use think\Controller;
use think\Request;

class Alipay extends Controller
{

    /**
     * @param Request $request
     * @return array|mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function payOrder(Request $request)
    {
        if ($request->isPost()) {
            //获取订单号
            $order_id = $request->param('order_id');
            $remark = $request->param('remark');
            $order = new Order();
            return $order->pay($order_id, $remark);
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

        if ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {

            $data['trade_status'] = 1;
            $data['pay_status'] = 1;
            $data['trade_no'] = $trade_no;
            $data['pay_time'] = date('y-m-d H:i:s', time());
            $result = db('order')->where(['out_trade_no' => $out_trade_no,])->update($data);//修改订单状态,支付宝单号到数据库

            if ($result) {
                return ['status' => 200, 'msg' => '成功！！'];
            } else {
                return ['status' => 400, 'msg' => '失败！！'];
            }

        } else {
            return ['status' => 400, 'msg' => '失败！！'];
        }
    }
}