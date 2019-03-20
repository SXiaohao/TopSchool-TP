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

    $jwtToken=\think\facade\Cache::get($phone);
    if ($token!=$jwtToken) {
        die("token与用户不符");
    }
    $key = config('token_key');//解密秘钥
    $type = "HS256";//签名类型HS256
    // JWT::$leeway = 10;//偏移时间
    try {
        $json = Firebase\JWT\JWT::decode($jwtToken, $key, array($type));//解密签名token
        return $json;
    } catch (\Exception $exception) {//如果解密失败，或者超过有效期则die
        die("token已过期");
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
function uc_time_ago($ptime)
{
    date_default_timezone_set('PRC');
    $ptime = strtotime($ptime);
    $etime = time() - $ptime;
    switch ($etime) {
        case $etime <= 24 * 60 * 60:
            $msg = date('Ymd', $ptime) == date('Ymd', time()) ? '今天 ' . date('H:i', $ptime) : '昨天 ' . date('H:i', $ptime);
            break;
        case $etime > 24 * 60 * 60 && $etime <= 2 * 24 * 60 * 60:
            $msg = date('Ymd', $ptime) + 1 == date('Ymd', time()) ? '昨天 ' . date('H:i', $ptime) : '前天 ' . date('H:i', $ptime);
            break;
        default:
            $msg = date('m月d日 ', $ptime);
    }
    return $msg;
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