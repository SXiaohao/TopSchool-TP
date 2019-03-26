<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/26
 * Time: 13:20
 */

namespace app\common\model;


use think\Db;
use think\Model;

class Chat extends Model
{
    /**
     * 上传消息记录
     * @param $request
     * @return bool
     */
    public function uploadChat($request)
    {
        return $this->save(['id' => $request->id,
            'name' => $request->name,
            'face' => $request->face,
            'to_id' => $request->to_id,
            'msg' => $request->msg,
            'date' =>  date('y-m-d H:i:s', time()),
            'ctype' => $request->ctype]);
    }

    /**
     * 上传图片
     * @param $file
     * @return array
     */
    public function uploadImg($file)
    {
        //上传图片并返回访问路径
        $info = $file->validate(['ext', 'jpg,jpeg,png,gif'])->move('../public/static/chat');
        if ($info) {
            $getSaveName=str_replace("\\","/",$info->getSaveName());
            $imagePath = config('local_path') . "/static/chat/" . $getSaveName;
        } else {
            return ['status' => 400,
                'msg' => $file->getError()];
        }

        return ['status' => 200,
            'msg' => '上传成功！',
            'imagePath' => $imagePath];
    }

    /**
     * 上传图片
     * @param $file
     * @return array
     */
    public function uploadVoice($file)
    {
        //上传图片并返回访问路径
        $info = $file->move('../public/static/voice');
        if ($info) {
            $getSaveName=str_replace("\\","/",$info->getSaveName());
            $voicePath = config('local_path') . "/static/voice/" . $getSaveName;
        } else {
            return ['status' => 400,
                'msg' => $file->getError()];
        }

        return ['status' => 200,
            'msg' => '上传成功！',
            'voicePath' => $voicePath];
    }

    public function historyRecord($id,$to_id,$page){
        $history=Db::field('*')->table('ym_chat')
            ->where(['id'=>$id ,'to_id'=>$to_id]||['id'=>$to_id ,'to_id'=>$id])
            ->order('date','DESC')
            ->page($page,10)
            ->select();
        sort($history);
      return $history;

    }

    public function getMsgList(){

    }
}