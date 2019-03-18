<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/16
 * Time: 21:51
 */

namespace app\confession\controller;


use app\confession\model\Confession;
use think\Controller;
use think\Request;


class Card extends Controller
{

    public function index(Request $request)
    {
        if ($request->isGet()) {
            $page = $request->param('page');
            $Confession = new Confession();
            return $Confession->findOfAll($page);
        }
        return config('PARAMS_ERROR');
    }
}