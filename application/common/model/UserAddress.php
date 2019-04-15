<?php


namespace app\common\model;


use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

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
                ->select();
            return ['status' => 200, 'msg' => '查询成功！！',
                'address' => $address["address"],
                'phone' => $address["phone"]];
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
        return ['status'=>400,'msg'=>'查询失败！！'];
    }

    public function addAddress()
    {

    }
}