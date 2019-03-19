<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/16
 * Time: 21:41
 */

namespace app\confession\model;


use app\confession\controller\Publish;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Model;

class ConfessionImage extends Model
{
    const LOCAL_PATH = 'http://127.0.0.1';

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

        $User = Db::table('ym_user')->where('phone', $phone)->find();
        /*if ($User[0]["token"] != $token) {
            return ['status' => 400,
                'msg' => '用户token不符合'];
        }*/
        if ($files != null) {

            $article_id = Db::table('ym_confession')
                ->insertGetId(['user_id' => $User["user_id"], 'content' => $content, 'release_time' => date('y-m-d h:i:s', time()), 'id' => $User["user_id"]]);
            $imagePath = null;
            $i = 0;
            foreach ($files as $file) {
                $info = $file->validate(['ext', 'jpg,png,gif'])->move('../public/static/images');
                if ($info) {
                    $imagePath[$i] = ConfessionImage::LOCAL_PATH . '/static/images/' . $info->getSaveName();
                    Db::table('ym_confession_image')
                        ->insert(['article_id' => $article_id, 'image_path' => $imagePath[$i++]]);
                } else {
                    return ['status' => 400,
                        'msg' => $files->getError()];
                }
            }
            return ['image_path' => $imagePath];
        } else {
            $status = Db::table('ym_confession')
                ->insert(['user_id' => $User["user_id"], 'content' => $content, 'release_time' => date('y-m-d h:i:s', time()), 'id' => $User["user_id"]]);
            if ($status == 1) {
                return ['status' => 200,
                    'msg' => '上传成功！！'];
            } else {
                return ['status' => 400,
                    'msg' => '上传失败！！'];
            }
        }
    }
}