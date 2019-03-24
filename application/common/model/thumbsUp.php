<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/23
 * Time: 13:56
 */

namespace app\common\model;


use think\Db;
use think\Model;

class thumbsUp extends Model
{

    public function addThumbsUp($phone, $thumbs_up_type, $type_id, $token)
    {
        if (!checkToken($token, $phone)) {
            return config('NOT_SUPPORTED');
        }
        if (Db::table('ym_thumbsup')
                ->where(['phone' => $phone, 'thumbs_up_type' => $thumbs_up_type, 'type_id' => $type_id])->select() == null) {

            if (Db::table('ym_thumbsup')->insert(['phone' => $phone, 'thumbs_up_type' => $thumbs_up_type, 'type_id' => $type_id]) == 1) {
                if ($thumbs_up_type == 'article') {
                    Db::table('ym_confession')->where('article_id', $type_id)->setInc('thumbs_up');
                    return ['status' => 200, 'msg' => '点赞成功！！'];
                }
                Db::table('ym_confession_comment')->where('comment_id', $type_id)->setInc('thumbs_up');
                return ['status' => 200, 'msg' => '点赞成功！！'];
            }
            return ['status' => 400, 'msg' => '点赞失败！！'];
        } else {

            if ($thumbs_up_type == 'article') {
                Db::table('ym_confession')
                    ->where('article_id', $type_id)->setDec('thumbs_up');
                Db::table('ym_thumbsup')
                    ->where(['phone' => $phone, 'thumbs_up_type' => $thumbs_up_type, 'type_id' => $type_id])->delete();
                return ['status' => 200, 'msg' => '取消成功！！'];
            }
            Db::table('ym_confession_comment')->where('comment_id', $type_id)->setDec('thumbs_up');
            Db::table('ym_thumbsup')
                ->where(['phone' => $phone, 'thumbs_up_type' => $thumbs_up_type, 'type_id' => $type_id])->delete();

            return ['status' => 200, 'msg' => '取消成功！！'];
        }

    }

}