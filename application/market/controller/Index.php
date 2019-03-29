<?php

/**
 * Created by jiangjun on 2019/3/18 21:21
 */

namespace app\market\controller;

use think\Controller;
use think\Request;
use app\market\model\Market;

class Index extends Controller
{
    /**
     * 注册超市
     * @param Request $request
     * @return mixed
     */
    public function regMarket(Request $request)
    {
        if ($request->isPost()) {
            $Market = new Market();
            return $Market->regMarket($request);
        }
        return config('PARAMS_ERROR');
    }

    /**
     * 超市列表查询
     * @param Request $request
     * @return mixed
     */
    public function findOfType(Request $request)
    {
        if ($request->isPost()) {
            $page = $request->param('page');
            $order = $request->param('order');
            $type = $request->param('type');
            $priceOrder = $request->param('priceOrder');
            $market_school = $request->param('market_school');
            $market = new Market();
           return $market->getMarketList($page, $order, $type, $priceOrder, $market_school);
        }
        return config('PARAMS_ERROR');
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
            $Confession = new Market();
            return $Confession->findOfAll($page);
        }
        return config('PARAMS_ERROR');
    }

    /*
     * 测试中
     */
    public function schType(Request $request)
    {
        if ($request->isPost()) {

            $school = $request->param('market_school');
            $type = $request->param('type');

            $Confession = new Market();
            return $Confession->schType($type, $school);
        }
        return config('PARAMS_ERROR');
    }

}