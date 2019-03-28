<?php

/**
 * Created by jiangjun on 2019/3/21 21:42
 */

namespace app\market\model;

use think\Db;
use think\Model;

class Productcates extends Model
{
    //const COUNT_OF_PAGE = 10;

    /**
     *添加分类
     * @param $productcates
     * @return bool
     */
    public function addProductcates($productcates)
    {
        $this->id = $productcates->id;   //id主键
        $this->ord = $productcates->ord;  //排序
        $this->title = $productcates->title;  //分类名称
        $this->status = $productcates->status; //状态
        return $this->save();
    }


    /**
     * 查找类别
     * @param $title
     * @return mixed
     */
    public function schProductcate($title)
    {
        return Db::query("SELECT id,title FROM ym_productcates WHERE title = '$title'");
    }


    /*
     * SELECT ym_productcates.`title`,ym_product.`title`,ym_product.`pro_no`,ym_product.`keywords`,ym_product.`desc` FROM ym_productcates JOIN  ym_product
ON ym_productcates.`id` = ym_product.`cid` WHERE ym_productcates.`title` = '零食'
    根据类别查找商品
     */


    /**
     * 查找类别中的商品
     * @param $title
     * @return array
     */
    public function findType($title)
    {
        try {
            $Productcates = Db::query("SELECT ym_productcates.id,ym_productcates.title,ym_product.title,ym_product.pro_no,ym_product.keywords,ym_product.desc FROM ym_productcates JOIN  ym_product
ON ym_productcates.id = ym_product.cid where ym_productcates.title = '$title'");

            return ['productcatesList' => $Productcates,
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


    /**
     * 查找全部类别
     * @return mixed
     */
    public function selectAlltype()
    {
        return Db::query('SELECT id,title FROM ym_productcates');
    }


    /**
     * 删除分类
     * @param $title
     * @return int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function deleteProductcates($title)
    {
        return Db::table('ym_productcates')->where('title', $title)->delete();
    }

    /**
     * 更改商品类别
     * @param $title
     * @return int|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function updateProductcates($title,$newtitle)
    {
        return Db::table('ym_productcates')->where('title', $title)->update(['title' => $newtitle]);
    }

}