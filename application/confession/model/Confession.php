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
use think\Exception;
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
                ->where('ym_confession.article_id=ym_confession_image.article_id')
                ->where('ym_confession.status=1 ')
                ->group('ym_confession.article_id')
                ->order('ym_confession.release_time', 'DESC')
                ->page($page, 20)
                ->select();

            for ($i = 0; $i < count($Confession); $i++) {

                if (strlen($Confession[$i]["content"]) > 240) {
                    $Confession[$i]["content"] = mb_strcut($Confession[$i]["content"], 0, 240) . '...';
                }
                $Confession[$i]["content"] = userTextDecode($Confession[$i]["content"]);
                $Confession[$i]["release_time"] = uc_time_ago($Confession[$i]["release_time"]);
                if ($Confession[$i]["images_list"] != null) {
                    $Confession[$i]["images_list"] = explode(",", $Confession[$i]["images_list"]);
                }

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
     * @param $phone
     * @return array
     * @throws Exception
     */
    public function getArticleContent($article_id, $phone)
    {
        try {
            //查询文章内容
            $articleContent = Db::field('ym_confession.user_id, ym_user.user_name,avatar,ym_confession.article_id,content,release_time,reading_volume,thumbs_up,
                 GROUP_CONCAT(image_path)AS images_list ')
                ->table(['ym_user', 'ym_confession', 'ym_confession_image'])
                ->where('ym_confession.article_id = ym_confession_image.article_id ')
                ->where('ym_confession.user_id = ym_user.user_id')
                ->where('ym_confession_image.article_id =' . $article_id)
                ->select();
            //文章是否点过赞
            $articleContent[0]["thumbs_up_status"] = Db::table('ym_thumbsup')
                ->where(['phone' => $phone, 'type_id' => $article_id, 'thumbs_up_type' => 'article'])
                ->value('thumbs_up_status');
            $articleContent[0]["content"] = userTextDecode($articleContent[0]["content"]);
            //格式化文章发布时间
            $articleContent[0]["release_time"] = uc_time_ago($articleContent[0]["release_time"]);
            //图片字符串链接打散成字符串数组
            $articleContent[0]["images_list"] = explode(",", $articleContent[0]["images_list"]);

            if ($articleContent[0]["images_list"][0] == '') {
                $articleContent[0]["images_list"] = null;
            }
            //查询前5条评论
            $comment = Db::field('ym_confession_comment.* ,ym_user.user_name AS commentator_name,ym_user.avatar AS avatar')
                ->table(['ym_confession_comment', 'ym_user'])
                ->where('ym_user.user_id=ym_confession_comment.commentator_id')
                ->where('article_id =' . $article_id)
                ->page(1, 5)
                ->select();
            //浏览量+1
            Confession::where('article_id', $article_id)
                ->update(['reading_volume' => ['inc', 1]]);

            //判断文章是否有评论
            if (count($comment) > 0) {
                $comment = $this->foreachReply($comment, $phone);

                //数组改成对象
                return ['ArticleContent' => $articleContent[0],
                    'comment_list' => $comment,
                    'other' => '查看全部评论 ',
                    'status' => 200,
                    'msg' => "成功"];
            }

            return ['ArticleContent' => $articleContent[0],
                'comment_list' => [],
                'other' => '暂无评论',
                'status' => 200,
                'msg' => "成功"];

        } catch (DataNotFoundException $e) {
            var_dump($this->getLastSql());
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
     * @param $phone
     * @return array '文章的全部的评论'
     */
    public function getCommentAndReply($article_id, $phone)
    {
        try {
            //查询文章所有评论
            $comment = Db::field('ym_confession_comment.* ,ym_user.user_name AS commentator_name,ym_user.avatar AS avatar')
                ->table(['ym_confession_comment', 'ym_user'])
                ->where('ym_user.user_id=ym_confession_comment.commentator_id')
                ->where('article_id=' . $article_id)
                ->select();

            //填充回复
            $comment = $this->foreachReply($comment, $phone);
            return ['commentAndReplyList' => $comment,
                'length' => count($comment),
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
     * @param $phone
     * @return mixed
     */
    private function foreachReply($Comment, $phone)
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
                    $Reply[$j]["reply_content"] = userTextDecode($Reply[$j]["reply_content"]);
                }
                //是否点过赞
                $Comment[$i]["thumbs_up_status"] = Db::table('ym_thumbsup')
                    ->where(['phone' => $phone, 'type_id' => $Comment[$i]["comment_id"], 'thumbs_up_type' => 'comment'])
                    ->value('thumbs_up_status');
                $Comment[$i]["comment_time"] = uc_time_ago($Comment[$i]["comment_time"]);
                $Comment[$i]["reply_list"] = $Reply;
                $Comment[$i]["comment_content"] = userTextDecode($Comment[$i]["comment_content"]);

            } catch (DataNotFoundException $e) {
            } catch (ModelNotFoundException $e) {
            } catch (DbException $e) {
            }
        }
        return $Comment;
    }

    /**
     * 首页5条表白墙
     * @param $id
     * @return array
     */
    public function homePageOfFind($id)
    {
        try {
            $confessionList = Db::field('ym_confession.* ,ym_confession_image.image_path')
                ->table(['ym_confession', 'ym_confession_image'])
                ->where('status', 1)
                ->where('id', $id)
                ->where('ym_confession.article_id=ym_confession_image.article_id')
                ->where('image_path IS NOT NULL')
                ->order('reading_volume', 'DESC')
                ->group('ym_confession.article_id')
                ->limit(6)
                ->select();
            for ($i=0;$i<count($confessionList);$i++){
                $confessionList[$i]["content"]=userTextDecode( $confessionList[$i]["content"]);
            }
            return ['status' => 200, 'msg' => '查询成功！', 'confessionList' => $confessionList];
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }

        return ['status' => 400, 'msg' => '查询失败！'];
    }

}