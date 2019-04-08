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
            $market_id = $request->param('market_id');
            $order = new Order();
            return $order->getAmount($market_id);
        }
        return config('PARAMS_ERROR');

    }
}