<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/16
 * Time: 21:41
 */

namespace app\confession\model;


use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Model;

class Confession extends Model
{
    const COUNT_OF_PAGE = 20;

    /**
     * 表白墙文章列表
     *
     * @param $page '页码'
     * @return array
     */
    public function findOfAll($page)
    {
        try {
            $Confession = Db::field('ym_confession.*, ym_user.user_name,avatar,GROUP_CONCAT(image_path)AS images_list')
                ->table(['ym_user', 'ym_confession', 'ym_confession_image'])
                ->where('ym_confession.user_id=ym_user.user_id')
                ->where( 'ym_confession.article_id=ym_confession_image.article_id')
                ->group('ym_confession.article_id')
                ->order('ym_confession.release_time', 'DESC')
                ->page($page, 20)
                ->select();

            for ($i = 0; $i < count($Confession); $i++) {
                $Confession[$i]["content"] = mb_strcut($Confession[$i]["content"], 0, 240) . '...';
                $Confession[$i]["release_time"] = uc_time_ago($Confession[$i]["release_time"]);
                $Confession[$i]["images_list"] = explode(",", $Confession[$i]["images_list"]);
            }
            return ['cardsList' => $Confession,
                'totalPages' => ceil(Db::table('ym_confession')->count('*') / Confession::COUNT_OF_PAGE),
                'status' => 200,
                'msg' => "成功"];
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
        return ['status' => 400,
            'msg' => "查询失败"];
    }

    /**
     * 文章内容 附带 5条评论
     *
     * @param $article_id '文章id'
     * @return array
     */
    public function getArticleContent($article_id)
    {
        try {
            $ArticleContent = Db::field('ym_confession.user_id, ym_user.user_name,avatar,ym_confession.article_id,content,release_time,reading_volume,thumbs_up,
                 GROUP_CONCAT(image_path)AS images_list ')
                ->table(['ym_user', 'ym_confession', 'ym_confession_image'])
                ->where('ym_confession.article_id = ym_confession_image.article_id ')
                ->where('ym_confession.user_id = ym_user.user_id')
                ->where('ym_confession_image.article_id =' . $article_id)
                ->select();
            $ArticleContent[0]["release_time"] = uc_time_ago($ArticleContent[0]["release_time"]);
            $ArticleContent[0]["images_list"] = explode(",", $ArticleContent[0]["images_list"]);
            $Comment = Db::field('ym_confession_comment.* ,ym_user.user_name AS commentator_name,ym_user.avatar AS avatar')
                ->table(['ym_confession_comment', 'ym_user'])
                ->where('ym_user.user_id=ym_confession_comment.commentator_id')
                ->where('article_id =' . $article_id)
                ->page(1, 5)
                ->select();

            if (count($Comment) > 0) {
                $Comment = $this->foreachReply($Comment);
                return ['ArticleContent' => array_to_object($ArticleContent,"article"),//改成对象
                    'comment_list'=>$Comment,
                    'other' => '查看全部评论 ',
                    'status' => 200,
                    'msg' => "成功"];
            }
            return ['ArticleContent' => $ArticleContent,
                'comment_list'=>[],
                'other' => '暂无评论',
                'status' => 200,
                'msg' => "成功"];

        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
        return ['status' => 400,
            'msg' => "查询失败"];
    }

    /**
     * 评论详情页
     *
     * @param $article_id '文章id'
     * @return array '文章的全部的评论'
     */
    public function getCommentAndReply($article_id)
    {
        try {
            $Comment = Db::field('ym_confession_comment.* ,ym_user.user_name AS commentator_name,ym_user.avatar AS avatar')
                ->table(['ym_confession_comment', 'ym_user'])
                ->where('ym_user.user_id=ym_confession_comment.commentator_id')
                ->where('article_id=' . $article_id)
                ->select();
            $Comment = $this->foreachReply($Comment);
            return ['commentAndReplyList' => $Comment,
                'length' => count($Comment),
                'status' => 200,
                'msg' => "成功"];
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
        return ['status' => 400,
            'msg' => "查询失败"];
    }









    /**
     * 将回复填充进评论链表
     * @param $Comment '评论链表'
     * @return mixed
     */
    private function foreachReply($Comment)
    {
        for ($i = 0; $i < count($Comment); $i++) {
            try {
                $Reply = Db::field('t1.*, t2.user_name AS replier_name,t3.user_name AS toReplier_name ')
                    ->table(['ym_confession_reply ' => 't1'])
                    ->join(['ym_user' => 't2'], ' t1.replier_id = t2.user_id ')
                    ->join(['ym_user ' => 't3 '], ' t1.toReplier_id = t3.user_id ')
                    ->where(' comment_id= ' . $Comment[$i]["comment_id"])
                    ->select();
                for ($j = 0; $j < count($Reply); $j++) {
                    $Reply[$j]["reply_time"] = uc_time_ago($Reply[$j]["reply_time"]);
                }
                $Comment[$i]["comment_time"] = uc_time_ago($Comment[$i]["comment_time"]);
                $Comment[$i]["reply_list"] = $Reply;
            } catch (DataNotFoundException $e) {
            } catch (ModelNotFoundException $e) {
            } catch (DbException $e) {
            }

        }
        return $Comment;

    }

}