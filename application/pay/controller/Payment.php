<?php


namespace app\pay\controller;


use app\pay\Alipay;
use think\Config;
use think\Controller;

class Payment extends Controller
{

    public function payOrder()
    {
        //获取订单号
        $where['id'] = input('post.orderid');
        //查询订单信息db('order')->where($where)->find()
        $order_info = 'sdasdsadsdsasa';
        //$order_info['ordersn']
        $reoderSn ='sdasdsadsdsasa' ;
        //获取支付金额
        $money = 0.01;
            //$order_info['realprice'];
        //判断支付方式

        //$type['paytype'] = 1;

        //db('order')->where($where)->update($type);


        $alipay = new Alipay();

        //异步回调地址
        $url = 'http://127.0.0.1/pay/payment/alipaynotify';

        $array = $alipay->alipay('源梦', $money, $reoderSn, $url);


        if ($array) {
            return [$array, 1, '成功'];
        } else {

            return ['', 0, '对不起请检查相关参数'];
        }

    }

    /**
     * 支付宝支付回调修改订单状态
     */
    public function alipay_notify()
    {
        //原始订单号
        $out_trade_no = input('out_trade_no');
        //支付宝交易号
        $trade_no = input('trade_no');
        //交易状态
        $trade_status = input('trade_status');


        if ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {

            $condition['ordersn'] = $out_trade_no;
            $data['status'] = 2;
            $data['third_ordersn'] = $trade_no;

            $result = db('order')->where($condition)->update($data);//修改订单状态,支付宝单号到数据库

            if ($result) {
                echo 'success';
            } else {
                echo 'fail';
            }

        } else {
            echo "fail";
        }


    }


}