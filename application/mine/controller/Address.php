<?php


namespace app\mine\controller;


use app\common\model\UserAddress;
use think\Controller;
use think\Exception;
use think\exception\PDOException;
use think\Request;

class Address extends Controller
{
    /**
     * 更新用户地址
     * @param Request $request
     * @return array|mixed
     * @throws Exception
     * @throws PDOException
     */
    public function update(Request $request)
    {
        if ($request->isPost()) {
            $user_id = $request->param('user_id');
            $phone = $request->param('phone');
            $address = $request->param('address');
            $name = $request->param('name');
            $city = $request->param('city');
            $userAddress = new UserAddress();
            return $userAddress->updateAddress($user_id, $phone, $address, $name, $city);
        }
        return config('PARAMS_ERROR');
    }

}