<?php
/**
 * Created by PhpStorm.
 * User: SXiaohao
 * Date: 2019/3/17
 * Time: 18:24
 */

namespace app\confession\controller;


use app\common\model\thumbsUp;
use app\confession\model\Confession;
use app\confession\model\ConfessionComment;
use app\confession\model\ConfessionReply;
use think\Controller;
use think\Request;

class Article extends Controller
{

    /**
     * 获取文章内容
     * @param Request $request
     * @return array|mixed
     * @throws \think\Exception
     */
    public function getContent(Request $request)
    {
        if ($request->isGet()) {
            $article_id = $request->param('article_id');
            $phone=$request->param('phone');
            $Article = new Confession();
            return $Article->getArticleContent($article_id,$phone);
        }
        return config('PARAMS_ERROR');
    }


    /**
         * 获取评论&回复
     * @param Request $request
     * @return array|mixed
     */
    public function getComment(Request $request)
    {
        if ($request->isGet()) {
            $article_id = $request->param('article_id');
            $phone=$request->param('phone');
            $article = new Confession();
            return $article->getCommentAndReply($article_id,$phone);
        }
        return config('PARAMS_ERROR');
    }

    /**
     * 添加评论
     * @param Request $request
     * @return array|mixed
     */
    public function addComment(Request $request)
    {
        if ($request->isPost()) {
            $article_id = $request->param('article_id');
            $phone = $request->param('phone');
            $token = $request->param('token');
            $comment_content = $request->param('comment_content');

            $comment = new ConfessionComment();
            return $comment->
            addComment($article_id, $phone, $token, $comment_content);
        }
        return config('PARAMS_ERROR');
    }


    /**
     * 添加回复
     * @param Request $request
     * @return array|mixed
     */
    public function replyComment(Request $request)
    {
        if ($request->isPost()) {
            $comment_id = $request->param('comment_id');
            $replier_phone = $request->param('phone');
            $toReplier_id = $request->param('commentator_id');
            $token = $request->param('token');
            $reply_content = $request->param('reply_content');

            $reply = new ConfessionReply();
            return $reply->addReply($comment_id, $replier_phone, $toReplier_id, $token, $reply_content);
        }
        return config('PARAMS_ERROR');
    }

    /**
     * 添加回复
     * @param Request $request
     * @return array|mixed
     */
    public function reply(Request $request)
    {
        if ($request->isPost()) {
            $comment_id = $request->param('comment_id');
            $replier_phone = $request->param('phone');
            $toReplier_id = $request->param('toReplier_id');
            $token = $request->param('token');
            $reply_content = $request->param('reply_content');

            $reply = new ConfessionReply();
            return $reply->addReply($comment_id, $replier_phone, $toReplier_id, $token, $reply_content);
        }
        return config('PARAMS_ERROR');
    }

    public function addThumbsUp(Request $request){
        if ($request->isPost()){
            $comment_id=$request->param('comment_id');
            $phone = $request->param('phone');
            $article_id = $request->param('article_id');
            $token=$request->param('token');
            $thumbsUp=new thumbsUp();
            if ($article_id!=null){
               return $thumbsUp->addThumbsUp($phone,'article',$article_id,$token);
            }
            return $thumbsUp->addThumbsUp($phone,'comment',$comment_id,$token);
        }
        return config('PARAMS_ERROR');
    }
}