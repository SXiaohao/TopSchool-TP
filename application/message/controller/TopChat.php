<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/26
 * Time: 13:18
 */

namespace app\message\controller;

use app\message\model\Chat;
use app\message\model\ChatList;
use think\Controller;
use think\Request;

class TopChat extends Controller
{
    /**
     *
     * @param Request $request
     */
    public function record(Request $request)
    {
        if ($request->isPost()) {
            $chat = new Chat();
            $chat->uploadChat($request);

        }
        //return config('PARAMS_ERROR');
    }

    /**
     * 记录图片
     * @param Request $request
     * @return array|mixed
     */
    public function recordImg(Request $request)
    {
        if ($request->isPost()) {
            $file = $request->file('file');
            $chat = new Chat();
            return $chat->uploadImg($file);
        }
        return config('PARAMS_ERROR');
    }

    /**
     * 记录语音
     * @param Request $request
     * @return array|mixed
     */
    public function recordVoice(Request $request)
    {
        if ($request->isPost()) {
            $file = $request->file('file');
            $chat = new Chat();
            return $chat->uploadVoice($file);
        }
        return config('PARAMS_ERROR');
    }

    /**
     * 获取历史记录
     * @param Request $request
     * @return array|mixed
     */
    public function history(Request $request)
    {
        if ($request->isPost()) {
            $id = $request->param('id');
            $to_id = $request->param('to_id');
            $page = $request->param('page');
            $chat = new Chat();
            return $chat->historyRecord($id, $to_id, $page);
        }
        return config('PARAMS_ERROR');
    }

    /**
     * 记录消息列表
     * @param Request $request
     * @return array|mixed
     */
    public function recordList(Request $request)
    {
        if ($request->isPost()) {
            $msgList = $request->param('msgList');
            $chatList = new ChatList();
            return $chatList->updateList($msgList);
        }
        return config('PARAMS_ERROR');
    }

    /**
     * 获取消息列表
     * @param Request $request
     * @return array|mixed
     */
    public function getList(Request $request)
    {
        if ($request->isGet()) {
            $id = $request->param('id');
            $chatList = new ChatList();
            return $chatList->selectList($id);
        }
        return config('PARAMS_ERROR');
    }

    public function status(Request $request)
    {
        if ($request->isGet()) {
            $id = $request->param('id');
            $to_id = $request->param('to_id');
            $chat = new Chat();
            $chat->updateStatus($id, $to_id);
        }
    }


}