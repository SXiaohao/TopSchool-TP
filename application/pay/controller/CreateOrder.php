<?php


namespace app\pay\controller;


use app\pay\model\Order;
use think\Controller;
use think\Request;

class CreateOrder extends Controller
{
    public function create(Request $request)
    {
        if ($request->isPost()) {
            $itemList = $request->param('shopping_cart');
            $buy_id = $request->param('user_id');
            $order = new Order();
            return $order->createOrder($itemList, $buy_id);
        }
        return config('PARAMS_ERROR');
    }
}