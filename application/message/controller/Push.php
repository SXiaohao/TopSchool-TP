<?php


namespace app\message\controller;


use app\message\model\UniPushUtils;
use app\message\Utils\pushMessageAll;
use think\Controller;
use think\Request;

class Push extends Controller
{
    public function chat(Request $request)
    {
        if ($request->isPost()){
            $push=new pushMessageAll();
            return $push->pushMessageToAppOfNotification('源梦网络','闲来无事！！');
        }
        return config('PARAMS_ERROR');
    }
}