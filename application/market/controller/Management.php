<?php


namespace app\market\controller;


use app\pay\model\Order;
use think\Controller;
use think\Request;

class Management extends Controller
{
    /**
     * 金额
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

    public function productAdd(Request $request)
    {
        if ($request->isPost()) {
            $product = new \app\market\model\Product();
            return $product->addProduct($request);

        }
        return config('PARAMS_ERROR');
    }

    public function productDel(Request $request)
    {
        if ($request->isPost()){
            $token=$request->param('token');
            $phone=$request->param('phone');
            $user_id=$request->param('user_id');
            $product_id=$request->param('id');
            $product=new \app\market\model\Product();
            return $product->delProduct($token,$phone,$user_id,$product_id);
        }
        return config('PARAMS_ERROR');
    }
}