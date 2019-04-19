<?php


namespace app\pay\controller;


use think\Controller;
use think\Request;

class Refund extends Controller
{
    public function create(Request $request){
        if ($request->isPost()){


        }
        return config('PARAMS_ERROR');
    }
    public function dispose(Request $request){
        if ($request->isPost()){

        }
        return config('PARAMS_ERROR');
    }
}