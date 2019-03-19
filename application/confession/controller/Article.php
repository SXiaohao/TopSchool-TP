<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/17
 * Time: 18:24
 */

namespace app\confession\controller;


use app\confession\model\Confession;
use think\Controller;
use think\Request;

class Article extends Controller
{
    public function getContent(Request $request)
    {
        if ($request->isGet()) {
            $article_id = $request->param('article_id');
            $Article = new Confession();
            return $Article->getArticleContent($article_id);
        }
        return config('PARAMS_ERROR');
    }

    public function getComment(Request $request){
        if($request->isGet()){
            $article_id=$request->param('article_id');
            $Article=new Confession();
            return $Article->getCommentAndReply($article_id);
        }
        return config('PARAMS_ERROR');
    }
}