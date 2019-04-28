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
    public function notify()
    {
        //原始订单号
        $out_trade_no = input('out_trade_no');
        //支付宝交易号
        $trade_no = input('trade_no');
        //交易状态
        $trade_status = input('trade_status');

        $pay_amount = input('total_amount');

        $order = new Order();
        try {
            $order->aliUpdateOrder($out_trade_no, $trade_no, $trade_status, $pay_amount);
        } catch (PDOException $e) {
        } catch (Exception $e) {
        }
    }
}