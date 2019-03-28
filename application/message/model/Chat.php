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

        $msg=$request->msg;
        return $this->save(['id' => $request->id,
            'name' => $request->name,
            'avatar' => $request->avatar,
            'to_id' => $request->to_id,
            'msg' => $this->userTextEncode($msg),
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
        try {
            $history = Db::field('*')->table('ym_chat')
                ->where(['id' => $id, 'to_id' => $to_id] || ['id' => $to_id, 'to_id' => $id])
                ->order('date', 'DESC')
                ->page($page, 10)
                ->select();
            for ($i=0;$i<count($history);$i++){
                $history[$i]["msg"]=$this->userTextDecode($history[$i]["msg"]);
            }
            sort($history);
            return ['status'=>200,'msg'=>'查询成功！！','history'=>$history];
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
        return ['status'=>400,'msg'=>'查询失败！！'];

    }


    public function updateStatus($id,$to_id){
        Db::name('chat')
            ->where(['id'=>$to_id,'to_id'=> $id])
            ->update(['is_read' => 1]);
    }

    /**
     * 把用户输入的文本转义（主要针对特殊符号和emoji表情）
     * @param $str
     * @return mixed|string
     */
    function userTextEncode($str){
        if(!is_string($str))return $str;
        if(!$str || $str=='undefined')return '';

        $text = json_encode($str); //暴露出unicode
        $text = preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i",function($str){
            return addslashes($str[0]);
        },$text); //将emoji的unicode留下，其他不动，这里的正则比原答案增加了d，因为我发现我很多emoji实际上是\ud开头的，反而暂时没发现有\ue开头。
        return json_decode($text);
    }
    /**
    解码上面的转义
     */
    function userTextDecode($str){
        $text = json_encode($str); //暴露出unicode
        $text = preg_replace_callback('/\\\\\\\\/i',function($str){
            return '\\';
        },$text); //将两条斜杠变成一条，其他不动
        return json_decode($text);
    }
}