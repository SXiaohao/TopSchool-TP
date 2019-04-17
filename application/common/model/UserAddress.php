<?php


namespace app\common\model;


use think\Db;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\exception\PDOException;

class UserAddress
{
    /**
     * @param $user_id
     * @return array
     */
    public function selectAddress($user_id)
    {
        try {
            $address = Db::table('ym_user_address')
                ->where('user_id', $user_id)
                ->value('address');
            $phone = Db::table('ym_user_address')
                ->where('user_id', $user_id)
                ->value('phone');
            $name = Db::table('ym_user_address')
                ->where('user_id', $user_id)
                ->value('name');
            return ['status' => 200, 'msg' => '查询成功！！',
                'address' => $address,
                'phone' => $phone,
                'name' => $name];
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
        return ['status' => 400, 'msg' => '查询失败！！'];
    }

    /**
     * 添加地址
     * @param $user_id
     * @param $phone
     * @param $address
     * @return array
     * @throws Exception
     * @throws PDOException
     */
    public function updateAddress($user_id, $phone, $address,$name)
    {
        if (Db::table('ym_user_address')->where('user_id', $user_id)
                ->select() != null) {
            $status = Db::table('ym_user_address')->where('user_id', $user_id)
                ->update(['address' => $address, 'phone' => $phone,'name'=>$name]);
            if ($status > 0) {
                return ['status' => 200, 'msg' => '更新成功！！'];
            }
            return ['status' => 400, 'msg' => '更新失败！！'];
        }
        $status = Db::table('ym_user_address')
            ->insert(['user_id' => $user_id, 'phone' => $phone, 'address' => $address]);
        if ($status > 0) {
            return ['status' => 200, 'msg' => '添加成功！！'];
        }
        return ['status' => 400, 'msg' => '添加失败！！'];
    }
}