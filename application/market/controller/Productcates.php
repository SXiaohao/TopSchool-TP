<?php

/**
 * Created by jiangjun on 2019/3/21 21:41
 */

namespace app\market\controller;

use think\Controller;
use think\Request;

class Productcates extends Controller
{
    /**
     * 添加分类
     * @param Request $request
     * @return bool|mixed
     */
    public function addProductcates(Request $request)
    {
        if ($request->isGet()) {
            return config('PARAMS_ERROR');
        }
        $Productcates = new \app\market\model\Productcates();
        return $Productcates->addProductcates($request);
    }

    /**
     * 查找类别
     * @param Request $request
     * @return mixed
     */
    public function schProductcate(Request $request)
    {
        if ($request->isGet()) {
            return config('PARAMS_ERROR');
        }
        $Productcates = new Productcates();
        return $Productcates->schProductcate($request->param('title'));
    }

    /**
     * 根据类别查找商品
     * @param Request $request
     * @return array|mixed
     */
    public function findType(Request $request)
    {
        if ($request->isGet()) {
            return config('PARAMS_ERROR');
        }
        $productsch = new Productcates();
        return $productsch->findType($request->param('title'));
    }

    /**
     * 查找全部类别
     * @param Request $request
     * @return mixed
     */
    public function selectAlltype(Request $request)
    {
        if ($request->isGet()) {
            return config('PARAMS_ERROR');
        }
        $productselect = new \app\market\model\Productcates();
        return $productselect->selectAlltype($request);
    }


    /**
     * 删除商品类别
     * @param Request $request
     * @return int|mixed
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function deleteProductcates(Request $request)
    {
        if ($request->isGet()) {
            return config('PARAMS_ERROR');
        }
        $productselect = new \app\market\model\Productcates();
        return $productselect->deleteProductcates($request->param('title'));
    }

    /**
     * 修改商品类别
     * @param Request $request
     * @return mixed
     */
    public function updateProductcates(Request $request)
    {
        if ($request->isGet()) {
            return config('PARAMS_ERROR');
        }

    }




}