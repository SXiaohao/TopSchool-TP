<?php

/**
 * Created by jiangjun on 2019/3/18 21:21
 */

namespace app\market\controller;

use think\Controller;
use think\Request;

class Market extends Controller
{
    /**
     * 注册超市
     * @param Request $request
     * @return mixed
     */
    public function regMarket(Request $request)
    {
        if ($request->isPost()) {
            return config('PARAMS_ERROR');
        }
        $Market = new \app\market\model\Market();
        return $Market->regMarket($request);
    }


    /**
     * 精确查找超市
     * @param Request $request
     * @return array|mixed
     */
    public function schMarket(Request $request)
    {
        if ($request->isPost()) {
            return config('PARAMS_ERROR');
        }
        $Market = new \app\market\model\Market();
        return $Market->schMarket($request->param('market_name'));
    }


    /**
     * 分页查询
     * @param Request $request
     * @return array|mixed
     */
    public function findOfAll(Request $request)
    {
        if ($request->isPost()) {
            $page = $request->param('page');
            $Confession = new \app\market\model\Market();
            return $Confession->findOfAll($page);
        }
        return config('PARAMS_ERROR');
    }


    /*
     * 测试中
     */
    public function schType(Request $request){
        if ($request->isPost()) {

            $school = $request->param('market_school');
            $type = $request->param('type');

            $Confession = new \app\market\model\Market();
            return $Confession->schType($type,$school);
        }
        return config('PARAMS_ERROR');
    }

}