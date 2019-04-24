<?php


namespace app\common\controller;


use app\confession\model\Confession;
use think\Controller;
use think\Request;

class Index extends Controller
{
    /**
     * @param Request $request
     * @return array|void
     */
    public function confession(Request $request){
        if ($request->isGet()){
            $confession=new Confession();
            $id=$request->param('id');
            return $confession->homePageOfFind($id);
        }
        return config('PARAMS_ERROR');
    }
}