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
     * 删除商品
     * @param $token
     * @param $phone
     * @param $user_id
     * @param $product_id
     * @return array
     */
    public function delProduct($token, $phone, $user_id, $product_id)
    {
        if (!checkToken($token,$phone)){
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