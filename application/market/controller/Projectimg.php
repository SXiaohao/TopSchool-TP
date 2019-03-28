<?php

/**
 * Created by jiangjun on 2019/3/21 21:54
 */

namespace app\market\controller;


use think\Controller;
use think\Request;

class Projectimg extends Controller
{
    /**
     * 添加商品图片
     * @param Request $request
     * @return bool|mixed
     */
    public function addProjectimg(Request $request){
        if($request->isGet()){
            return config('PARAMS_ERROR');
        }
        $Projectimg = new \app\market\model\Projectimg();
        return $Projectimg->addProjectimg($request);
    }
}