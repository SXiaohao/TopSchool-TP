<?php

/**
 * Created by jiangjun on 2019/3/21 21:42
 */

namespace app\market\model;

use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\exception\PDOException;
use think\Model;

class Productcates extends Model
{

    /**
     * 查找类别
     * @param $market_id
     * @return mixed
     */
    public function getCategory($market_id)
    {
        try {
            $catesList = Db::table('ym_productcates')
                ->where(['market_id' => $market_id])
                ->select();
            for ($i=0;$i<count($catesList);$i++){
                $catesList[$i]["count"]=Db::table('ym_product')
                    ->where('cateid',$catesList[$i]["cateid"])
                    ->count();
            }
            return ['msg' => '查询成功!!', 'status' => 200, 'catesList' => $catesList];
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }

        return ['msg' => '查询失败!!', 'status' => 400];
    }

    /**
     *添加分类
     * @param $catesList
     * @param $market_id
     * @return array
     */
    public function addProductcates($catesList, $market_id)
    {
        $ord = 1;
        foreach ($catesList as $item) {
            if (Db::table('ym_productcates')->insert(['ord' => $ord++,
                    'title' => $item["title"], 'market_id' => $market_id]) < 1) {
                return ['status' => 400, 'msg' => '添加失败！！'];
            }
        }
        return ['status' => 200, 'msg' => '添加成功！！'];
    }

    /**
     * 修改分类
     * @param $cateList
     * @param $phone
     * @param $token
     * @param $market_id
     * @return array
     */
    public function updateCategory($cateList, $phone, $token,$market_id)
    {
        if (!checkToken($token, $phone)) {
            return config('NOT_SUPPORTED');
        }
        if ($market_id == null) {
            return ['status' => 400, 'msg' => '超市id为空！！'];
        }
        try {
            Db::table('ym_Productcates')->where('market_id', $market_id)->delete();
            foreach ($cateList as $item) {
                Db::table('ym_Productcates')
                    ->insert(['cateid'=>$item["cateid"],
                        'ord' => $item["ord"],
                        'title' => $item["title"],
                        'market_id' => $item["market_id"]]);
            }
            return ['status' => 200, 'msg' => '更新成功！！'];
        } catch (PDOException $e) {
        } catch (Exception $e) {
        }
        var_dump($this->getLastSql());
        return ['status' => 400, 'msg' => '更新失败！！'];
    }

    /**
     * 获取商品列表
     * @param $market_id
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getProductList($market_id)
    {
        $cate = Db::table('ym_productcates')->where(['market_id' => $market_id])
            ->order('ord', 'ASC')->select();
        $cateproducts = null;

        foreach ($cate as $value) {
            $cateproducts["cateproducts" . $value["cateid"]] = $this->getProduct($value["market_id"], $value["cateid"]);
        }
        return ['status' => 200, 'msg' => '查询成功！！', 'allProduct' => $cateproducts, 'mainCate' => $cate];
    }

    /**
     * 获取商品信息
     * @param $market_id
     * @param $cateid
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    private function getProduct($market_id, $cateid)
    {
        return Db::table('ym_product')->where(['market_id' => $market_id, 'cateid' => $cateid])->select();
    }

}