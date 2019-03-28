<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/28
 * Time: 13:01
 */

namespace app\message\model;


use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\exception\PDOException;

class ChatList
{
    public function updateList($msgList)
    {
        $id = $msgList[0]["id"];
        try {
            Db::table('ym_chat_list')->where('id', $id)->delete();
            foreach ($msgList as $msg) {
                Db::table('ym_chat_list')
                    ->data(['id' => $msg["id"],
                        'to_id' => $msg["toId"],
                        'avatar' => $msg["avatar"],
                        'name' => $msg["name"],
                        'msg' => $msg["msg"],
                        'status' => $msg["status"],
                        'unread' => $msg["unread"]
                    ])
                    ->insert();
            }
            return ['status' => 200, 'msg' => '添加成功！！'];
        } catch (PDOException $e) {
        } catch (Exception $e) {
        }
        return ['status' => 400, 'msg' => '添加失败！！'];
    }

    public function selectList($id)
    {
        try {
            $msgList = Db::table('chat_list')->where('id', $id)->find();
            return ['status'=>200,'msg'=>'查询成功！！','msgList'=>$msgList];
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
        return ['status'=>400,'msg'=>'查询失败！！'];
    }
}