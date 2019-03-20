<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/16
 * Time: 22:09
 */

namespace app\common\model;

use think\Model;
class User extends Model
{
    /**
     * 查询手机号是否存在，存在返回当前对象，不存在返回null
     * @param $phone
     * @return array|\PDOStatement|string|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function findByPhone($phone){
        return User::where('phone',$phone)->find();
    }

    /**
     * 插入一条用户信息
     * @param Model $request
     * @return bool
     */
    public function register($request){
        $this->phone = $request->phone;
        $this->user_name = $request->user_name;
        $this->avatar = $request->avatar;
        $this->password = passSalt($request->password);
        $this->sex = $request->sex;
        $this->id = $request->id;
        $this->status = 1;
        $this->add_date = date("Y-m-d h:i:s");
        return $this->save();
    }
}