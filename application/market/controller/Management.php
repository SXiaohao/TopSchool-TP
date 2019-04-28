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
     * 查询金额
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

    /**
     * 添加商品
     * @param Request $request
     * @return array|mixed
     */
    public function productAdd(Request $request)
    {
        if ($request->isPost()) {
            $product = new \app\market\model\Product();
            return $product->addProduct($request);
        }
        return config('PARAMS_ERROR');
    }

    /**
     * 删除商品
     * @param Request $request
     * @return array|mixed
     */
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
     * 更新商品
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

    /**
     * 查询商品分类
     * @param Request $request
     * @return mixed
     */
    public function productCate(Request $request)
    {
        if ($request->isGet()) {
            $market_id = $request->param('market_id');
            $category = new Productcates();
            return $category->getCategory($market_id);
        }
        return config('PARAMS_ERROR');
    }

    /**
     * 查询商品
     * @param Request $request
     * @return array|mixed
     */
    public function product(Request $request)
    {
        if ($request->isGet()) {
            $product_id = $request->param('product_id');
            $product = new \app\market\model\Product();
            return $product->selProduct($product_id);
        }
        return config('PARAMS_ERROR');
    }

    /**
     * 查询订单
     * @param Request $request
     * @return array|mixed
     */
    public function order(Request $request)
    {
        if ($request->isPost()) {
            $market_id = $request->param('market_id');
            $phone = $request->param('phone');
            $token = $request->param('token');
            $type = $request->param('type');
            $page = $request->param('page');
            $order = new Order();
            return $order->selectOrder($market_id, $phone, $token, $type, $page);
        }
        return config('PARAMS_ERROR');
    }

    /**
     * 查询订单详情
     * @param Request $request
     * @return array|mixed
     */
    public function orderItem(Request $request)
    {
        if ($request->isPost()) {
            $order_id = $request->param('order_id');
            $phone = $request->param('phone');
            $token = $request->param('token');
            $order = new Order();
            return $order->selectOrderItem($order_id, $phone, $token);
        }
        return config('PARAMS_ERROR');
    }

    /**
     * 处理订单
     * @param Request $request
     * @return mixed
     */
    public function orderDispose(Request $request)
    {
        if ($request->isPost()) {
            $phone = $request->param('phone');
            $token = $request->param('token');
            $order_id = $request->param('order_id');
            $order = new Order();
            return $order->dispose($phone, $token, $order_id);
        }
        return config('PARAMS_ERROR');
    }
}