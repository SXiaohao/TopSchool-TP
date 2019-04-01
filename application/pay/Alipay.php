<?php


namespace app\pay;


use AlipayTradeAppPayRequest;
use AopClient;
use think\Config;

class Alipay
{

    /*
     * 支付宝支付
     * $body            名称
     * $total_amount    价格
     * $product_code    订单号
     * $notify_url      异步回调地址
     */
    public function alipay($body, $total_amount, $product_code, $notify_url)
    {

        /**
         * 调用支付宝接口。
         */
        require '../extend/alipay/aop/AopClient.php';
        require '../extend/alipay/aop/request/AlipayTradeAppPayRequest.php';

        $aop = new AopClient();

        $aop->gatewayUrl = Config('alipay')["gatewayUrl"];
        $aop->appId = Config('alipay')['appId'];
        $aop->rsaPrivateKey = Config('alipay')['rsaPrivateKey'];
        $aop->format = Config('alipay')['format'];
        $aop->charset = Config('alipay')['charset'];
        $aop->signType = Config('alipay')['signType'];
        $aop->alipayrsaPublicKey = Config('alipay')['alipayrsaPublicKey'];


        $request = new AlipayTradeAppPayRequest();
        $arr['body'] = '余额充值';
        $arr['subject'] = '充值';
        $arr['out_trade_no'] = $product_code;
        $arr['timeout_express'] = '30m';
        $arr['total_amount'] = floatval($total_amount);
        $arr['total_amount'] = '0.01'; //订单总金额
        $arr['product_code'] = 'QUICK_MSECURITY_PAY';

        $json = json_encode($arr);
        $request->setNotifyUrl($notify_url);
        $request->setBizContent($json);

        $response = $aop->sdkExecute($request);
        return $response;

    }

}