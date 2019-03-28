<?php

/**
 * Created by jiangjun on 2019/3/21 21:49
 */

namespace app\market\model;


use think\Model;

class Projectimg extends Model
{
    /**
     * 添加商品图片
     * @param $projectimg
     * @return bool
     */
    public function addProjectimg($projectimg){
        $this->id = $projectimg->id;   //id主键
        $this->productid = $projectimg->productid;  //排序
        $this->img = $projectimg->img;  //分类名称
        return $this->save();
    }
}