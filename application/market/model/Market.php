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
     * 注册超市
     * @param $market
     * @return mixed
     */
    public function regMarket($market)
    {
        return $this->save(['user_id' => $market->user_id,
            'market_name' => $market->market_name,
            'market_school' => $market->market_school,
            'dorm_tower' => $market->dorm_tower,
            'dorm_num' => $market->dorm_num,
            'type' => $market->type,
            'star_level' => 1,
            'status' => 1,
            'add_date' => date('Y-m-d H:i:s'),
            'notice' => $market->notice]);
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


    /**
     * @param $page
     * @param $order
     * @param $type
     * @param $sale_volume
     * @param $market_school
     * @return array
     */
    public function getMarketList($page, $order, $type, $sale_volume, $market_school)
    {
        if ($sale_volume == 1) {
            $sale_volume = 'DESC';
        } else {
            $sale_volume = 'ASC';
        }
        if ($type == "全部") {
            $totalPages = ceil(Db::table('ym_market')
                    ->where(['market_school' => $market_school])
                    ->count('*') / Market::COUNT_OF_PAGE);
        } else {
            $totalPages = ceil(Db::table('ym_market')
                    ->where(['market_school' => $market_school, 'type' => $type])
                    ->count('*') / Market::COUNT_OF_PAGE);
        }
        try {
            switch ($order) {
                case "综合":
                    if ($type == "全部") {
                        $marketList = Db::table('ym_market')
                            ->field('*,star_level+reading_volume as num')
                            ->where(['market_school' => $market_school])
                            ->order('num', 'DESC')
                            ->order('sale_volume', $sale_volume)
                            ->page($page, 10)
                            ->select();
                        for ($i=0;$i<count($marketList);$i++){
                            $marketList[$i]["notice"]=explode("|", $marketList[$i]["notice"]);
                        }

                    } else {
                        $marketList = Db::table('ym_market')
                            ->field('*,star_level+reading_volume as num')
                            ->where(['market_school' => $market_school, 'type' => $type])
                            ->order('num', 'DESC')
                            ->order('sale_volume', $sale_volume)
                            ->page($page, 10)
                            ->select();
                        for ($i=0;$i<count($marketList);$i++){
                            $marketList[$i]["notice"]=explode("|", $marketList[$i]["notice"]);
                        }
                    }
                    return ['status' => 200, 'msg' => '查询成功！！',
                        'marketList' => $marketList, 'totalPages' => $totalPages];
                case "星级":
                    if ($type == "全部") {
                        $marketList = Db::table('ym_market')
                            ->where(['market_school' => $market_school])
                            ->order('star_level', 'DESC')
                            ->order('sale_volume', $sale_volume)
                            ->page($page, 10)
                            ->select();
                        for ($i=0;$i<count($marketList);$i++){
                            $marketList[$i]["notice"]=explode("|", $marketList[$i]["notice"]);
                        }
                    } else {
                        $marketList = Db::table('ym_market')
                            ->where(['market_school' => $market_school, 'type' => $type])
                            ->order('star_level', 'DESC')
                            ->order('sale_volume', $sale_volume)
                            ->page($page, 10)
                            ->select();
                        for ($i=0;$i<count($marketList);$i++){
                            $marketList[$i]["notice"]=explode("|", $marketList[$i]["notice"]);
                        }
                    }
                    return ['status' => 200, 'msg' => '查询成功！！',
                        'marketList' => $marketList, 'totalPages' => $totalPages];
                case "人气":
                    if ($type == "全部") {
                        $marketList = Db::table('ym_market')
                            ->where(['market_school' => $market_school])
                            ->order('reading_volume', 'DESC')
                            ->order('sale_volume', $sale_volume)
                            ->page($page, 10)
                            ->select();
                        for ($i=0;$i<count($marketList);$i++){
                            $marketList[$i]["notice"]=explode("|", $marketList[$i]["notice"]);
                        }
                    } else {
                        $marketList = Db::table('ym_market')
                            ->where(['market_school' => $market_school, 'type' => $type])
                            ->order('reading_volume', 'DESC')
                            ->order('sale_volume', $sale_volume)
                            ->page($page, 10)
                            ->select();
                        for ($i=0;$i<count($marketList);$i++){
                            $marketList[$i]["notice"]=explode("|", $marketList[$i]["notice"]);
                        }
                    }
                    return ['status' => 200, 'msg' => '查询成功！！',
                        'marketList' => $marketList, 'totalPages' => $totalPages];
                default:
                    break;
            }
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
        return ['status' => 400, 'msg' => '查询失败！！'];
    }
}