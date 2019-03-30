<?php

/**
 * Created by jiangjun on 2019/3/20 22:36
 */

namespace app\market\model;


use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Model;

class Product extends Model
{

    /**
     * 添加商品
     * @param $product
     * @return bool
     */
    public function addProduct($product)
    {
        $this->id = $product->id;    //商品id
        $this->cid = $product->cid;    //分类
        $this->title = $product->title;   //商品名称
        $this->pro_no = $product->pro_no;   //商品编码
        $this->keywords = $product->keywords;  //关键字
        $this->desc = $product->desc;   //描述
        $this->imag = $product->imag;    //商品主图
        $this->price = $product->price;   //商品价格
        $this->cost = $product->cost;  //成本
        $this->pv = $product->pv;  //点击量

        return $this->save();
    }


    /**
     * 准确搜索商品
     * @param $title
     * @return array
     */
    public function schProduct($title)
    {

        return Db::query("SELECT ym_product.id,ym_product.title,ym_product.img,ym_product.price FROM ym_product WHERE title='$title'");

    }

    /**
     * 删除商品
     * @param $title
     * @return int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function deleteProduct($title)
    {
        return Db::table('ym_product')->where('title', $title)->delete();
    }

    /*
     * 修改商品
     */
    public function updateProduct($title, $keywords, $desc, $price, $cost)
    {
        return Db::table('ym_product')->where('title', $title)->update(['keywords' => $keywords, 'desc' => $desc, 'price' => $price, 'cost' => $cost]);
    }



}