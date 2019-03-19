<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/17
 * Time: 16:54
 */

namespace app\common\controller;

use think\Controller;
use think\Request;

class School extends Controller
{
    public function getSchool(Request $request){
        if($request->isGet()){
            return config('PARAMS_ERROR');
        }
        $School = new \app\common\model\School();
        return $School->getSchool($request);
    }
}