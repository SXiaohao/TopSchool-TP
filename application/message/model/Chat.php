<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/26
 * Time: 13:20
 */

namespace app\message\model;


use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
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

        $msg = $request->msg;
        return $this->save(['id' => $request->id,
            'name' => $request->name,
            'avatar' => $request->avatar,
            'to_id' => $request->to_id,
            'msg' => userTextEncode($msg),
            'date' => date('y-m-d H:i:s', time()),
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
            $getSaveName = str_replace("\\", "/", $info->getSaveName());
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
            $getSaveName = str_replace("\\", "/", $info->getSaveName());
            $voicePath = config('local_path') . "/static/voice/" . $getSaveName;
        } else {
            return ['status' => 400,
                'msg' => $file->getError()];
        }

        return ['status' => 200,
            'msg' => '上传成功！',
            'voicePath' => $voicePath];
    }

    public function historyRecord($id, $to_id, $page)
    {
        try {
            $history = Db::field('*')->table('ym_chat')
                ->where(['id' => $id, 'to_id' => $to_id] || ['id' => $to_id, 'to_id' => $id])
                ->order('date', 'DESC')
                ->page($page, 10)
                ->select();
            for ($i = 0; $i < count($history); $i++) {
                $history[$i]["msg"] = userTextDecode($history[$i]["msg"]);
            }
            sort($history);
            return ['status' => 200, 'msg' => '查询成功！！', 'history' => $history];
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
        return ['status' => 400, 'msg' => '查询失败！！'];

    }


    public function updateStatus($id, $to_id)
    {
        Db::name('chat')
            ->where(['id' => $to_id, 'to_id' => $id])
            ->update(['is_read' => 1]);
    }


}