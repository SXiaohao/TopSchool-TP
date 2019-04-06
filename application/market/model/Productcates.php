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
     * 查找类别
     * @param $market_id
     * @return mixed
     */
    public function getCategory($market_id)
    {
        try {
            $catesList = Db::table('ym_productcates')
                ->where(['market_id' => $market_id, 'status' => 1])
                ->select();
            return ['msg' => '查询成功!!', 'status' => 400, 'catesList' => $catesList];
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }

        return ['msg' => '查询失败!!', 'status' => 400];
    }



    /**
     * 删除分类
     * @param $title
     * @return int
     * @throws Exception
     * @throws PDOException
     */
    public function deleteProductcates($title)
    {
        return Db::table('ym_productcates')->where('title', $title)->delete();
    }

    /**
     * 更改商品类别
     * @param $title
     * @return int|string
     * @throws Exception
     * @throws PDOException
     */
    public function updateProductcates($title, $newtitle)
    {
        return Db::table('ym_productcates')->where('title', $title)->update(['title' => $newtitle]);
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
    public function getProduct($market_id, $cateid)
    {
        return Db::table('ym_product')->where(['market_id' => $market_id, 'cateid' => $cateid])->select();
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
        $cate = Db::table('ym_productcates')->where(['market_id' => $market_id, 'status' => 1])
            ->order('ord', 'ASC')->select();
        $cateproducts = null;

        foreach ($cate as $value) {
            $cateproducts["cateproducts" . $value["cateid"]] = $this->getProduct($value["market_id"], $value["cateid"]);
        }

        return ['status' => 200, 'msg' => '查询成功！！', 'allProduct' => $cateproducts, 'mainCate' => $cate];
    }
}