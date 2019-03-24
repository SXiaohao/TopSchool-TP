<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/16
 * Time: 21:42
 */

namespace app\confession\model;


use app\common\model\User;
use think\Db;

class ConfessionReply
{
    /**
     * 添加回复
     * @param $comment_id
     * @param $replier_phone
     * @param $toReplier_id
     * @param $token
     * @param $reply_content
     * @return array
     */
    public function addReply($comment_id, $replier_phone, $toReplier_id, $token, $reply_content)
    {
       //验证token
        if (!checkToken($token, $replier_phone)){
            return config('NOT_SUPPORTED');
        }
        $replier_id = User::where('phone', $replier_phone)->value('user_id');

        if ($replier_id != null && $toReplier_id != null) {
            if (Db::name('confession_reply')
                ->data(['comment_id' => $comment_id, 'replier_id' => $replier_id,
                    'reply_content' => $reply_content, 'toReplier_id' => $toReplier_id,
                    'reply_time' => date('y-m-d H:i:s', time())])
                ->insert()) {
                return ['status' => 200, 'msg' => '回复成功！！'];
            }
        }
        return ['status' => 400, 'msg' => '回复失败！！'.$toReplier_id."comment_id:".$comment_id];
    }
}