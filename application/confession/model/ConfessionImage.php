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
   private const LOCAL_PATH = 'http://127.0.0.1';

    /**
     * @param $files
     * @param $content
     * @param $token
     * @param $phone
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function uploadArticle($files, $content, $token, $phone)
    {

        $User = Db::table('ym_user')->where('phone', $phone)->find();
        if ($User[0]["token"] != $token) {
            return ['status' => 400,
                'msg' => '用户token不符合'];
        }
        if ($files != null) {
            $article_id = Db::name('ym_confession')
                ->insertGetId([`user_id` => $User[0]["user_id"], `content` => $content, `release_time` => time(), `id` => $User[0]["user_id"]]);
            $imagePath = null;
            foreach ($files as $file) {
                $info = $file->validate(['ext', 'jpg,png,gif'])->move('../public/static/images');
                if ($info) {
                    $imagePath = ConfessionImage::LOCAL_PATH . '/static/images/' . $info->getSaveName();
                    Db::name('ym_confession_image')
                        ->insert(['article_id' => $article_id, 'image_path' => $imagePath]);
                } else {
                    return ['status' => 202,
                        'msg' => $files->getError()];
                }
            }
        } else {
            Db::name('ym_confession')
                ->insert([`user_id` => $User[0]["user_id"], `content` => $content, `releaseTime` => time(), `id` => $User[0]["user_id"]]);
        }
        return [config('PARAMS_ERROR')];

    }
}