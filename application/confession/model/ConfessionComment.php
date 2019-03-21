<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/16
 * Time: 21:41
 */

namespace app\confession\model;


use app\common\model\User;
use think\Db;
use think\Model;

class ConfessionComment extends Model
{


    /**
     * 添加评论
     * @param $article_id
     * @param $phone
     * @param $token
     * @param $comment_content
     * @return array
     */
    public function addComment($article_id, $phone, $token, $comment_content)
    {
        //checkToken($token, $phone);
        $commentator_id= User::where('phone', $phone)->value('user_id');
        if ($commentator_id != null) {
            if (Db::name('confession_comment')
                ->data(['commentator_id' => $commentator_id, 'article_id' => $article_id,
                    'comment_content' => $comment_content, 'comment_time' => date('y-m-d H:i:s', time())])
                ->insert()) {
                return ['status' => 200, 'msg' => '评论成功！！'];
            }
        }
        return ['status' => 400, 'msg' => '评论失败！！'];
    }


    /**
     * 点赞
     * @param $id
     * @return array
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function addThumbUp($id)
    {
        //点赞数+1
        if (ConfessionComment::where('comment_id', $id)
                ->update(['thumbs_up' => ['inc', 1]]) == 1) {
            return ['status' => 200, 'msg' => '点赞成功！！'];
        }
        return ['status' => 400, 'msg' => '点赞失败！！'];
    }

}