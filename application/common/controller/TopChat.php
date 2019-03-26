<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/26
 * Time: 13:18
 */

namespace app\common\controller;

use app\common\model\Chat;
use think\Controller;
use think\Request;

class TopChat extends Controller
{
    public function record(Request $request)
    {
        if ($request->isPost()) {
            $chat = new Chat();
            $chat->uploadChat($request);

        }
        //return config('PARAMS_ERROR');
    }

    public function recordImg(Request $request)
    {
        if ($request->isPost()) {
            $file = $request->file('file');
            $chat = new Chat();
            return $chat->uploadImg($file);
        }
        return config('PARAMS_ERROR');
    }

    public function recordVoice(Request $request)
    {
        if ($request->isPost()) {
            $file = $request->file('file');
            $chat = new Chat();
            return $chat->uploadVoice($file);
        }
        return config('PARAMS_ERROR');
    }

    public function history(Request $request)
    {
        if ($request->isPost()) {
            $id = $request->param('id');
            $to_id=$request->param('to_id');
            $page=$request->param('page');
            $chat = new Chat();
            return $chat->historyRecord($id,$to_id,$page);
        }
        return config('PARAMS_ERROR');
    }
}