<?php


namespace app\common\model;


use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\exception\PDOException;
use think\Model;

class UserAddress extends Model
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
                ->select();

            return  $address[0];
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
     * @param $name
     * @param $city
     * @return array
     * @throws Exception
     * @throws PDOException
     */
    public function updateAddress($user_id, $phone, $address, $name, $city)
    {
        $status = Db::table('ym_user_address')
            ->where('user_id', $user_id)
            ->update(['address' => $address, 'phone' => $phone,
                'name' => $name, 'city' => $city]);
        if ($status > 0) {
            return ['status' => 200, 'msg' => '更新成功！！'];
        }
        return ['status' => 400, 'msg' => '更新失败！！'];


    }

    public function addAddress($user_id)
    {
        $status = Db::table('ym_user_address')
            ->insert(['user_id' => $user_id]);
        if ($status > 0) {
            return ['status' => 200, 'msg' => '添加成功！！'];
        }
        return ['status' => 400, 'msg' => '添加失败！！'];
    }
}