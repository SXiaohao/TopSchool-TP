<?php

/**
 * Created by jiangjun on 2019/3/18 21:21
 */

namespace app\market\controller;

use app\market\model\MarketVerify;
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
     * 上传身份证 学生证图片
     * @param Request $request
     * @return array|mixed
     */
    public function upload(Request $request)
    {
        if ($request->isPost()) {
            $card_front = $request->file('card_front');
            $card_back=$request->file('card_back');
            $student_card=$request->file('student_card');
            $marketVerify = new MarketVerify();
            return $marketVerify->getImgPath($card_front,$card_back,$student_card);
        }
        return config('PARAMS_ERROR');
    }

    /**
     * 验证信息
     * @param Request $request
     * @return array|mixed
     */
    public function insertInfo(Request $request)
    {
        if ($request->isPost()) {
            $marketVerify = new MarketVerify();
            return $marketVerify->insertInfo($request);
        }
        return config('PARAMS_ERROR');
    }

    /**
     * 获取是否为商家
     * @param Request $request
     * @return mixed
     */
    public function getMerchant(Request $request)
    {
        if ($request->isGet()) {
            $Market = new Market();
            $user_id = $request->param('user_id');
            return $Market->findOfPhone($user_id);
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
            $sale_volume = $request->param('sale_volume');
            $market_school = $request->param('market_school');
            $market = new Market();
            return $market->getMarketList($page, $order, $type, $sale_volume, $market_school);
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