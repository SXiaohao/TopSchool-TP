<?php


namespace app\common\controller;


use app\common\model\Search;
use think\Request;

class Seek
{
    public function searchMess(Request $request){
        if($request->isGet()){
            $title = $request->param('title');
            $id = $request->param('id');
            $search = new Search();
            return $search->searchMess($title,$id);
        }
        return config('PARAMS_ERROR');
    }


}