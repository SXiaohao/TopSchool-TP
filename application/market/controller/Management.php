<?php


namespace app\market\controller;


use app\market\model\Productcates;
use app\pay\model\Order;
use think\Controller;
use think\Exception;
use think\exception\PDOException;
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
        if ($request->isPost()) {
            $token = $request->param('token');
            $phone = $request->param('phone');
            $user_id = $request->param('user_id');
            $product_id = $request->param('id');
            $product = new \app\market\model\Product();
            return $product->delProduct($token, $phone, $user_id, $product_id);
        }
        return config('PARAMS_ERROR');
    }

    /**
     * @param Request $request
     * @return array|mixed
     * @throws Exception
     * @throws PDOException
     */
    public function productUpdate(Request $request)
    {
        if ($request->isPost()) {
            $product = new \app\market\model\Product();
            return $product->updateProduct($request);

        }
        return config('PARAMS_ERROR');
    }
    public function productCate(Request $request)
    {
        if ($request->isGet()) {
            $market_id = $request->param('market_id');
            $category = new Productcates();
            return $category->getCategory($market_id);
        }
        return config('PARAMS_ERROR');
    }

    public function product(Request $request)
    {
        if ($request->isGet()) {
            $product_id = $request->param('product_id');
            $product = new \app\market\model\Product();
            return $product->selProduct($product_id);
        }
        return config('PARAMS_ERROR');
    }
}