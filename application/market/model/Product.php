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
use think\exception\PDOException;
use think\Image;
use think\Model;

class Product extends Model
{

    /**
     * 添加商品
     * @param $product
     * @return array
     */
    public function addProduct($product)
    {
        if (!checkToken($product->token, $product->phone)) {
            return config('NOT_SUPPORTED');
        }
        $status = Db::table('ym_product')
            ->insert(['market_id' => $product->market_id,
                'cateid' => $product->cateid,
                'title' => $product->title,
                'keywords' => $product->keywords,
                'img' => $product->img,
                'price' => $product->price,
                'cost' => $product->cost]);
        if ($status) {
            return ['status' => 200, 'msg' => '添加成功！！'];
        }
        return ['status' => 200, 'msg' => '添加失败！！'];
    }

    /**
     * 更新商品
     * @param $product
     * @return array
     * @throws Exception
     * @throws PDOException
     */
    public function updateProduct($product)
    {
        /* if (!checkToken($product->token,$product->phone)){
             return config('NOT_SUPPORTED');
         }*/
        $status = Db::table('ym_product')
            ->where('id', $product->product_id)
            ->update(['market_id' => $product->market_id,
                'cateid' => $product->cateid,
                'title' => $product->title,
                'keywords' => $product->keywords,
                'img' => $product->img,
                'price' => $product->price,
                'cost' => $product->cost]);
        if ($status) {
            return ['status' => 200, 'msg' => '更新成功！！'];
        }
        return ['status' => 200, 'msg' => '更新失败！！'];
    }

    /**
     * 删除商品
     * @param $token
     * @param $phone
     * @param $user_id
     * @param $product_id
     * @return array
     */
    public function delProduct($token, $phone, $user_id, $product_id)
    {
        if (!checkToken($token, $phone)) {
            return config('NOT_SUPPORTED');
        }
        try {
            $market_id = Db::table('ym_market')->where('user_id', $user_id)->value('market_id');
            $product_market_id = Db::table('ym_product')->where('id', $product_id)->value('market_id');
            if ($market_id == $product_market_id) {
                Db::table('ym_product')->where('id', $product_id)->delete();
                return ['status' => 200, 'msg' => '删除成功！！'];
            }
            return ['status' => 400, 'msg' => '删除失败！！'];
        } catch (PDOException $e) {
        } catch (Exception $e) {
        }
        return ['status' => 400, 'msg' => '删除失败！！'];
    }


    /**
     * 上传图片
     * @param $file
     * @return array
     */
    public function uploadImg($file)
    {
        var_dump($file);
        //上传图片并返回访问路径
        $info = $file->validate(['size' => 1048576, 'ext', 'jpg,jpeg,png,gif'])->move('../public/static/product');
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

    public function selProduct($product_id)
    {
        try {
            $product = Db::table('ym_product')->where('id', $product_id)->select();
            if ($product != null) {
                return ['status' => 200, 'msg' => '查询成功！！', 'product' => $product[0]];
            }
            return ['status' => 400, 'msg' => '查询失败！！'];
        } catch (Exception $e) {
        }
        return ['status' => 400, 'msg' => '查询失败！！'];
    }

}