<?php

/**
 * Created by jiangjun on 2019/3/21 21:41
 */

namespace app\market\controller;

use app\market\model\Productcates;
use think\Controller;
use think\Db;
use think\Request;

class Category extends Controller
{
    /**
     * 添加分类
     * @param Request $request
     * @return bool|mixed
     */
    public function add(Request $request)
    {
        if ($request->isPost()) {
            $Productcates = new Productcates();
            $catesList = $request->param('catesList');
            $market_id = $request->param('market_id');
            return $Productcates->addProductcates($catesList, $market_id);
        }
        return config('PARAMS_ERROR');

    }

    /**
     * 查找类别
     * @param Request $request
     * @return mixed
     */
    public function select(Request $request)
    {
        if ($request->isGet()) {
            $productcates = new Productcates();
            $market_id = $request->param('market_id');
            return $productcates->getCategory($market_id);
        }
        return config('PARAMS_ERROR');

    }

    /**
     * 更新分类
     * @param Request $request
     * @return array
     */
    public function update(Request $request)
    {
        if ($request->isPost()) {
            $productcates = new Productcates();
            $cateList = $request->param('cateList');
            $phone=$request->param('phone');
            $token=$request->param('token');
            $market_id=$request->param('market_id');
            return $productcates->updateCategory($cateList,$phone,$token,$market_id);
        }
        return config('PARAMS_ERROR');
    }

}