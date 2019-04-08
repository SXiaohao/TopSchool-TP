<?php

/**
 * Created by jiangjun on 2019/3/20 22:40
 */

namespace app\market\controller;


use app\market\model\Productcates;
use think\Controller;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Request;

class Product extends Controller
{
    /**
     * 添加商品
     * @param Request $request
     * @return bool|mixed
     */
    public function add(Request $request)
    {
        if ($request->isPost()) {
            $Product = new \app\market\model\Product();
            return $Product->addProduct($request);

        }
        return config('PARAMS_ERROR');
    }

    /**
     * 上传商品图片
     * @param Request $request
     * @return array|mixed
     */
    public function upload(Request $request)
    {
        if ($request->isPost()) {
            $file = $request->file('file');
            $Product = new \app\market\model\Product();
            return $Product->uploadImg($file);
        }
        return config('PARAMS_ERROR');
    }

    /**
     * 查找商品
     * @param Request $request
     * @return array|mixed
     */
    public function schProduct(Request $request)
    {
        if ($request->isGet()) {
            return config('PARAMS_ERROR');
        }
        $product = new \app\market\model\Product();
        return $product->schProduct($request->param('title'));
    }



    /**
     * 修改商品
     * @param Request $request
     * @return mixed
     */
    public function updateProduct(Request $request)
    {
        if ($request->isGet()) {
            return config('PARAMS_ERROR');
        }
        $title = $request->param('title');
        $keywords = $request->param('keyword');
        $desc = $request->param('desc');
        $price = $request->param('price');
        $cost = $request->param('cost');
        $product = new \app\market\model\Product();
        return $product->updateProduct($title, $keywords, $desc, $price, $cost);
    }

    /**
     * 查询商品列表
     * @param Request $request
     * @return array|mixed
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public function select(Request $request)
    {
        if ($request->isGet()) {
            $market_id = $request->param('market_id');
            $productcates = new Productcates();
            return $productcates->getProductList($market_id);
        }
        return config('PARAMS_ERROR');
    }

}