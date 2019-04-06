<?php


namespace app\market\controller;


use app\pay\model\Order;
use think\Controller;
use think\Request;

class Management extends Controller
{
    /**
     * @param Request $request
     * @return array|mixed
     */
    public function amount(Request $request)
    {
        if ($request->isGet()) {
            $user_id = $request->param('user_id');
            $order = new Order();
            return $order->getAmount($user_id);
        }
        return config('PARAMS_ERROR');

    }
}