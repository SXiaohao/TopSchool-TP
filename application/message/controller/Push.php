<?php


namespace app\message\controller;


use app\message\model\UniPushUtils;
use think\Controller;
use think\Request;

class Push extends Controller
{
    public function chat(Request $request)
    {
        if ($request->isPost()){
            $push=new UniPushUtils();
            return $push->pushMessageToApp();
        }
        return config('PARAMS_ERROR');
    }
}