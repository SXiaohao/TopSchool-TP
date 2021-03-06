<?php


namespace app\pay\controller;


use app\common\model\UserAddress;
use app\pay\model\Order;
use app\pay\model\OrderItem;
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


    /**
     * 查询订单商品
     * @param Request $request
     * @return array|mixed
     */
    public function select(Request $request)
    {
        if ($request->isPost()) {
            $order_id = $request->param('order_id');
            $order_item = new OrderItem();
            return $order_item->getItemList($order_id);
        }
        return config('PARAMS_ERROR');
    }


}