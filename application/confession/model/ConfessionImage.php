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
use think\facade\Cache;
use think\Model;

class ConfessionImage extends Model
{

    /**
     * 文章上传
     * @param $files '图片'
     * @param $content '文章内容'
     * @param $token '用户token'
     * @param $phone '手机号'
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function uploadArticle($files, $content, $token, $phone)
    {
        //验证token
        if (!checkToken($token, $phone)) {
            return config('NOT_SUPPORTED');
        }
        if (Cache::get($phone) == $token) {
            return ['status' => 201, 'msg' => '30分钟内只能发布一条'];
        }
        //通过手机号查询用户信息
        $User = Db::table('ym_user')->where('phone', $phone)->find();

        //是否上传图片
        if ($files != null) {
            $article_id = Db::table('ym_confession')
                ->insertGetId(['user_id' => $User["user_id"], 'content' => userTextEncode($content),
                    'release_time' => date('y-m-d H:i:s', time()), 'id' => $User["id"]]);
            //上传图片并返回访问路径
            foreach ($files as $file) {
                $info = $file->validate(['ext', 'jpg,jpeg,png,gif'])->move('../public/static/images');
                if ($info) {
                    $imagePath = config('local_path') . '/static/images/' . $info->getSaveName();
                    Db::table('ym_confession_image')
                        ->insert(['article_id' => $article_id, 'image_path' => $imagePath]);
                } else {
                    return ['status' => 400,
                        'msg' => $files->getError()];
                }
            }
            Cache::set($phone, $token, 3600);
            return ['status' => 200,
                'msg' => '上传成功！'];
        } else {
            //上传文章&返回插入状态
            $status = Db::table('ym_confession')
                ->insertGetId(['user_id' => $User["user_id"], 'content' => userTextEncode($content), 'release_time'
                => date('y-m-d H:i:s', time()), 'id' => $User["id"]]);
            Db::table('ym_confession_image')
                ->insert(['article_id' => $status]);
            //判断上传是否成功
            if ($status) {
                Cache::set($phone, $token, 3600);
                return ['status' => 200,
                    'msg' => '上传成功！！'];
            } else {
                return ['status' => 400,
                    'msg' => '上传失败！！'];
            }
        }
    }
}