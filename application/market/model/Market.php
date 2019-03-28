<?php

/**
 * Created by jiangjun on 2019/3/18 21:20
 */

namespace app\market\model;

use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Model;

class Market extends Model
{
    const COUNT_OF_PAGE = 10;


    /**
     * 查找超市
     * @param $Market_Name
     * @return mixed
     */
    public function schMarket($Market_Name)
    {
        return Db::query("SELECT * FROM `ym_market` WHERE market_name LIKE '$Market_Name%'");
    }

    /**
     * 注册超市
     * @param $market
     * @return mixed
     */
    public function regMarket($market)
    {

        $this->user_id = $market->user_id;
        $this->market_name = $market->market_name;
        $this->market_school = $market->market_school;
        $this->dorm_tower = $market->dorm_tower;
        $this->dorm_num = $market->dorm_num;
        $this->type = $market->type;
        $this->star_level = 1;
        $this->status = 1;
        $this->add_date = date('Y-m-d H:i:s');
        $this->notice = $market->notice;


        return $this->save();
    }


    /**
     * 分页显示
     * @param $page
     * @return array
     */
    public function findOfAll($page)
    {
        try {
            $Market = Db::field('*')
                ->table('ym_market')
                ->page($page, 10)
                ->select();


            for ($i = 0; $i < count($Market); $i++) {
                $Market[$i]["notice"] = mb_strcut($Market[$i]["notice"], 0, 50) . '...';
            }

            return ['marketList' => $Market,
                'totalPages' => ceil(Db::table('ym_market')->count('*') / Market::COUNT_OF_PAGE),
                'status' => 200,
                'msg' => "成功"];
        } catch (DataNotFoundException $e) {
            var_dump($this->getLastSql());
        } catch (ModelNotFoundException $e) {
            var_dump($this->getLastSql());
        } catch (DbException $e) {
            var_dump($this->getLastSql());
        }
        return ['status' => 400,
            'msg' => "查询失败"];
    }


    /*
     * 测试
     */
    public function schType($type, $market_school)
    {
        if ($type == "学校") {
            try {
                $Market = Db::field('*')
                    ->table('ym_market')
                    ->page($type, 10)
                    ->select();


                for ($i = 0; $i < count($Market); $i++) {
                    $Market[$i]["notice"] = mb_strcut($Market[$i]["notice"], 0, 50) . '...';
                }

                return ['marketList' => $Market,
                    'totalPages' => ceil(Db::table('ym_market')->count('*') / Market::COUNT_OF_PAGE),
                    'status' => 200,
                    'msg' => "成功"];
            } catch (DataNotFoundException $e) {
                var_dump($this->getLastSql());
            } catch (ModelNotFoundException $e) {
                var_dump($this->getLastSql());
            } catch (DbException $e) {
                var_dump($this->getLastSql());
            }
            return ['status' => 400,
                'msg' => "查询失败"];
        } elseif ($type == "星级") {
            $Market = Db::query("SELECT * FROM `ym_market` WHERE market_school = '$market_school' ORDER BY star_level DESC ");


            for ($i = 0; $i < count($Market); $i++) {
                $Market[$i]["notice"] = mb_strcut($Market[$i]["notice"], 0, 50) . '...';
            }

            return ['marketList' => $Market,
                'totalPages' => ceil(Db::table('ym_market')->count('*') / Market::COUNT_OF_PAGE),
                'status' => 200,
                'msg' => "成功"];
        } elseif ($type == "评价") {

        } elseif ($type == "超市") {
            $Market = Db::query("SELECT * FROM `ym_market` WHERE market_school = '$market_school' AND type ='超市' ORDER BY star_level DESC ");


            for ($i = 0; $i < count($Market); $i++) {
                $Market[$i]["notice"] = mb_strcut($Market[$i]["notice"], 0, 50) . '...';
            }

            return ['marketList' => $Market,
                'totalPages' => ceil(Db::table('ym_market')->count('*') / Market::COUNT_OF_PAGE),
                'status' => 200,
                'msg' => "成功"];
        } elseif ($type == "外卖") {
            $Market = Db::query("SELECT * FROM `ym_market` WHERE market_school = '$market_school' AND type ='外卖' ORDER BY star_level DESC ");


            for ($i = 0; $i < count($Market); $i++) {
                $Market[$i]["notice"] = mb_strcut($Market[$i]["notice"], 0, 50) . '...';
            }

            return ['marketList' => $Market,
                'totalPages' => ceil(Db::table('ym_market')->count('*') / Market::COUNT_OF_PAGE),
                'status' => 200,
                'msg' => "成功"];

        } elseif ($type == "水果") {
            $Market = Db::query("SELECT * FROM `ym_market` WHERE market_school = '$market_school' AND type ='水果' ORDER BY star_level DESC ");


            for ($i = 0; $i < count($Market); $i++) {
                $Market[$i]["notice"] = mb_strcut($Market[$i]["notice"], 0, 50) . '...';
            }

            return ['marketList' => $Market,
                'totalPages' => ceil(Db::table('ym_market')->count('*') / Market::COUNT_OF_PAGE),
                'status' => 200,
                'msg' => "成功"];


        } elseif ($type == "其他") {
            $Market = Db::query("SELECT * FROM `ym_market` WHERE market_school = '$market_school' AND type ='其他' ORDER BY star_level DESC ");


            for ($i = 0; $i < count($Market); $i++) {
                $Market[$i]["notice"] = mb_strcut($Market[$i]["notice"], 0, 50) . '...';
            }

            return ['marketList' => $Market,
                'totalPages' => ceil(Db::table('ym_market')->count('*') / Market::COUNT_OF_PAGE),
                'status' => 200,
                'msg' => "成功"];

        }


    }


}