<?php


namespace app\mine\controller;


use app\common\model\UserAddress;
use think\Controller;
use think\Request;

class Address extends Controller
{
    public function select(Request $request)
    {
        if ($request->isGet()) {
            $user_id = $request->param('user_id');
            $address = new UserAddress();
            return $address->selectAddress($user_id);
        }
        return config('PARAMS_ERROR');
    }
}