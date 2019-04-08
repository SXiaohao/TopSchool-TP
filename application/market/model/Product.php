<?php

/**
 * Created by jiangjun on 2019/3/20 22:36
 */

namespace app\market\model;


use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\Model;

class Product extends Model
{

    /**
     * 添加商品
     * @param $productList
     * @return bool
     */
    public function addProduct($productList)
    {
        foreach ($productList as $item) {
            Db::table('ym_product')
                ->insert(['market_id' => $item["market_id"],
                    'cateid' => $item["cateid"],
                    'title' => $item["title"],
                    'keywords' => $item["keyword"],
                    'img' => $item["img"],
                    'price' => $item["price"],
                    'cost' => $item["cost"]]);
        }
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
     * @param $token
     * @param $user_id
     * @param $product_id
     * @return void
     */
    public function deleteProduct($token,$user_id,$product_id)
    {

    }

    /*
     * 修改商品
     */
    public function updateProduct($title, $keywords, $desc, $price, $cost)
    {
        return Db::table('ym_product')->where('title', $title)->update(['keywords' => $keywords, 'desc' => $desc, 'price' => $price, 'cost' => $cost]);
    }

    /**
     * 上传图片
     * @param $file
     * @return array
     */
    public function uploadImg($file)
    {
        //上传图片并返回访问路径
        $info = $file->validate(['ext', 'jpg,jpeg,png,gif'])->move('../public/static/product');
        if ($info) {
            $getSaveName = str_replace("\\", "/", $info->getSaveName());
            $imagePath = config('local_path') . "/static/product/" . $getSaveName;
        } else {
            return ['status' => 400,
                'msg' => $file->getError()];
        }

        return ['status' => 200,
            'msg' => '上传成功！',
            'imagePath' => $imagePath];
    }

}