<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
// 应用公共文件


use think\facade\Cache;

function getToken($phone)
{
    $key = config('token_key');
    $token = [
        "iss" => "",  //签发者 可以为空
        "aud" => "", //面象的用户，可以为空
        "iat" => time(), //签发时间
        "nbf" => time(), //在什么时候jwt开始生效
        "exp" => time() + 2592000, //token 过期时间 30天
        "phone" => $phone //记录的user手机号的信息，如果有其它信息，可以再添加数组的键值对
    ];
    return Firebase\JWT\JWT::encode($token, $key, "HS256"); //根据参数生成了 token
}

function checkToken($token, $phone)
{

    $jwtToken = Cache::get($phone);
    if ($token != $jwtToken) {
        return false;
        //die("token与用户不符:".$token."++++phone++++:".$phone);
    }
    $key = config('token_key');//解密秘钥
    $type = "HS256";//签名类型HS256
    // JWT::$leeway = 10;//偏移时间
    try {
        $json = Firebase\JWT\JWT::decode($jwtToken, $key, array($type));//解密签名token
        return $json;
    } catch (Exception $exception) {//如果解密失败，或者超过有效期则die
        return false;

    }
}

function sendSms($templateCode, $phone, $vCode)
{
    $code = new stdClass;
    $code->code = $vCode;
    AlibabaCloud\Client\AlibabaCloud::accessKeyClient('LTAIEAmtRB6q3vxv', 's1qtBFkbR7ThINpKMsdXHc47LKYfMb')
        ->regionId('cn-hangzhou')
        ->asGlobalClient();
    try {
        $result = AlibabaCloud\Client\AlibabaCloud::rpcRequest()
            ->product('Dysmsapi')
            ->version('2017-05-25')
            ->action('SendSms')
            ->method('POST')
            ->options([
                'query' => [
                    'RegionId' => 'cn-hangzhou',
                    'PhoneNumbers' => $phone,
                    'SignName' => '源梦科技',
                    'TemplateCode' => $templateCode,
                    'TemplateParam' => json_encode($code),
                ],
            ])
            ->request();
        if ($result->toArray()["Message"] === 'ok') {
            return true;
        } else {
            return $result->toArray()["Message"];
        }
    } catch (ClientException $e) {
        return $e->getErrorMessage() . PHP_EOL;
    } catch (ServerException $e) {
        return $e->getErrorMessage() . PHP_EOL;
    }
}

function passSalt($psw)
{
    $psw = md5($psw);
    $psw = crypt($psw, config('salt'));
    return $psw;
}

//时间格式化（时间戳）
function uc_time_ago($targetTime)
{
    $targetTime = strtotime($targetTime);
    // 今天最大时间
    $todayLast = strtotime(date('Y-m-d 23:59:59'));
    $agoTime = $todayLast - $targetTime;
    $agoDay = floor($agoTime / 86400);

    if ($agoDay == 0) {
        $result = '今天 ' . date('H:i', $targetTime);
    } elseif ($agoDay == 1) {
        $result = '昨天 ' . date('H:i', $targetTime);
    } elseif ($agoDay == 2) {
        $result = '前天 ' . date('H:i', $targetTime);
    } else {
        $result = date('m月d日 ', $targetTime);
    }
    return $result;

}

/**
 * 对象 转 数组
 *
 * @param object $obj 对象
 * @return array
 */
function object_to_array($obj)
{
    $obj = (array)$obj;
    foreach ($obj as $k => $v) {
        if (gettype($v) == 'resource') {
            return null;
        }
        if (gettype($v) == 'object' || gettype($v) == 'array') {
            $obj[$k] = (array)object_to_array($v);
        }
    }
    return $obj;
}

/**
 * 数组 转 对象
 *
 * @param array $arr 数组
 * @return object
 */
function array_to_object($arr)
{
    if (gettype($arr) != 'array') {
        return null;
    }
    foreach ($arr as $k => $v) {
        if (gettype($v) == 'array' || getType($v) == 'object') {
            $arr[$k] = (object)array_to_object($v);
        }
    }
    return (object)$arr;
}

/*
   * 支付宝支付
   * $body            名称
   * $total_amount    价格
   * $product_code    订单号
   * $notify_url      异步回调地址
   */
function alipay($body, $total_amount, $out_trade_no, $notify_url,$first_product)
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
    $arr['body'] = $body;
    $arr['subject'] = $first_product;
    $arr['out_trade_no'] = $out_trade_no;
    $arr['timeout_express'] = '30m';

    $arr['total_amount'] = floatval($total_amount);
    $arr['product_code'] = 'QUICK_MSECURITY_PAY';

    $json = json_encode($arr);
    $request->setNotifyUrl($notify_url);
    $request->setBizContent($json);

    $response = $aop->sdkExecute($request);
    return $response;

}

/**
 * 阿里退款
 * @param $out_trade_no
 * @param $refund_amount
 * @return bool
 * @throws Exception
 */
function alipayRefund($out_trade_no, $refund_amount){
    /**
     * 调用支付宝接口。
     */
    require '../extend/alipay/aop/AopClient.php';
    require '../extend/alipay/aop/request/AlipayTradeAppPayRequest.php';

    $aop = new AopClient ();
    $aop->gatewayUrl = Config('alipay')['gatewayUrl'];
    $aop->appId = Config('alipay')['appId'];
    $aop->rsaPrivateKey = Config('alipay')['rsaPrivateKey'];
    $aop->format = Config('alipay')['format'];
    $aop->charset = Config('alipay')['charset'];
    $aop->signType = Config('alipay')['signType'];
    $aop->alipayrsaPublicKey = Config('alipay')['alipayrsaPublicKey'];
    $request = new AlipayTradeRefundRequest();
    $request->setBizContent("{" .
//            "\"trade_no\":\"2017112821001004030523090753\"," .
        "\"out_trade_no\":\"$out_trade_no\"," .
        "\"refund_amount\":$refund_amount," .
        "\"refund_reason\":\"正常退款\","  .
        "\"operator_id\":\"OP001\"," .
        "\"store_id\":\"NJ_S_001\"," .
        "\"terminal_id\":\"NJ_T_001\"" .
        "  }");

    $result = $aop->execute ( $request );
    $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
    $resultCode = $result->$responseNode->code;

    if(!empty($resultCode)&&$resultCode == 10000){
        return true;
    } else {
        throw new Exception($result->$responseNode->sub_msg);
    }
}

/**
 * 微信支付
 * @param $body //名称
 * @param $total_amount //价格
 * @param $out_trade_no  //订单号
 * @return bool|false|string
 * @throws WxPayException
 */
function wepay($body, $total_amount, $out_trade_no)
{
    header('Access-Control-Allow-Origin: *');
    header('Content-type: text/plain');

    require_once "../extend/wxpayv3/WxPay.Api.php";
    require_once "../extend/wxpayv3/WxPay.Data.php";

    $total = floatval($total_amount);
    $total = round($total * 100); // 将元转成分

    if (empty($total)) {
        $total = 100;
    }
    $unifiedOrder = new WxPayUnifiedOrder();
    $unifiedOrder->SetBody($body);//商品或支付单简要描述
    $unifiedOrder->SetOut_trade_no($out_trade_no);
    $unifiedOrder->SetTotal_fee($total);
    $unifiedOrder->SetTrade_type('APP');

    $result = WxPayApi::unifiedOrder($unifiedOrder);
    if (is_array($result)) {
        return json_encode($result);
    }

    return false;
}

/**
 * 微信退款
 * @param $out_trade_no
 * @param $trade_no
 * @param $real_price
 * @param $pay_amount
 * @return bool|false|string
 * @throws WxPayException
 */
function wepayRefund($out_trade_no,$trade_no,$real_price,$pay_amount)
{
    header('Access-Control-Allow-Origin: *');
    header('Content-type: text/plain');

    require_once "../extend/wxpayv3/WxPay.Api.php";
    require_once "../extend/wxpayv3/WxPay.Data.php";

    $merchid = WxPayConfig::MCHID;

    $input = new WxPayRefund();
    $input->SetOut_trade_no($out_trade_no);			//自己的订单号
    $input->SetTransaction_id($trade_no);  	//微信官方生成的订单流水号，在支付成功中有返回
    $input->SetOut_refund_no(mt_rand(1000000,9999999));			//退款单号
    $input->SetTotal_fee($real_price*100);			//订单标价金额，单位为分
    $input->SetRefund_fee($pay_amount*100);			//退款总金额，订单总金额，单位为分，只能为整数
    $input->SetOp_user_id($merchid);

    $result = WxPayApi::refund($input);	//退款操作

    return $result;
}