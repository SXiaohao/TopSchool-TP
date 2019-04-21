<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/16
 * Time: 22:09
 */

namespace app\common\model;

use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\facade\Cache;
use think\Model;

class User extends Model
{
    /**
     * 查询手机号是否存在，存在返回当前对象，不存在返回null
     * @param $phone
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public function findByPhone($phone)
    {
        return User::where('phone', $phone)->find();
    }

    /**
     * 插入一条用户信息
     * @param Model $request
     * @return bool
     */
    public function register($request)
    {
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

    /**
     * @param $open_id
     * @param $type
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getOpenId($open_id, $type)
    {
        $login_type = Db::table('ym_user')
            ->where('open_id', $open_id)
            ->value('type');
        if ($login_type !== -1) {
            if ($login_type == $type) {
                $user = User::where('open_id', $open_id)->find();
                $School = new School();
                $School = $School->getBySchoolId($user->id);
                $token = getToken($user->phone);
                //缓存获取的token
                Cache::set($user->phone, $token);
                $address = new UserAddress();
                return ['status' => 200, 'msg' => '登录成功', 'user' => $user, 'school' => $School,
                    'token' => $token, 'addressInfo' => $address->selectAddress($user->user_id)];
            }
            return ['status' => 201, 'msg' => '用户未绑定!'];
        }
        return ['status' => 201, 'msg' => '用户未绑定！',];
    }

    /**
     * @param $phone
     * @param $password
     * @param $open_id
     * @param $type
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function bindUser($phone, $password, $open_id, $type)
    {
        $user = new User();
        $user = $user->findByPhone($phone);
        //如果用户存在
        if ($user) {
            if ($user->password === passSalt($password)) {//验证密码
                $School = new School();
                $School = $School->getBySchoolId($user->id);
                $token = getToken($phone);
                Cache::set($phone, $token);//缓存获取的token
                Db::table('ym_user')->where('phone', $phone)
                    ->update(['open_id' => $open_id, 'type' => $type]);
                $address = new UserAddress();
                return ['status' => 200, 'msg' => '登录成功', 'user' => $user->findByPhone($phone), 'school' => $School,
                    'token' => $token, 'addressInfo' => $address->selectAddress($user->user_id)];
            } else {
                return ['status' => 201, 'msg' => '手机号或密码错误!'];
            }
        } else {
            return ['status' => 202, 'msg' => '您的手机号未注册，请先注册!'];
        }
    }
}