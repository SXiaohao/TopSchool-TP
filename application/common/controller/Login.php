<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/16
 * Time: 22:04
 */

namespace app\common\controller;

use app\common\model\School;
use app\common\model\User;
use app\common\model\UserAddress;
use think\Controller;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\facade\Cache;
use think\Request;


class Login extends Controller
{
    public function login(Request $request)
    {
        $phone = $request->phone;
        $password = $request->password;
        $User = new User();
        $User = $User->findByPhone($phone);
        //如果用户存在
        if ($User) {
            if ($User->password === passSalt($password)) {//验证密码
                $School = new School();
                $School = $School->getBySchoolId($User->id);
                $token = getToken($phone);
                Cache::set($phone, $token);//缓存获取的token
                $address = new UserAddress();
                return ['status' => 200, 'msg' => '登录成功', 'user' => $User, 'school' => $School,
                    'token' => $token, 'addressInfo' => $address->selectAddress($User->user_id)];

            } else {
                return ['status' => 201, 'msg' => '手机号或密码错误!'];
            }
        } else {
            return ['status' => 202, 'msg' => '您的手机号未注册，请先注册!'];
        }
    }

    /**
     * 验证openId
     * @param Request $request
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function verifyOpenId(Request $request){
        if ($request->isGet()){
            $type=$request->param('type');
            $open_id=$request->param('openId');
            $user=new User();
            return $user->getOpenId($open_id,$type);
        }
        return config('PARAMS_ERROR');
    }

    /**
     * 绑定用户
     * @param Request $request
     * @return array|mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function binding(Request $request){
        if ($request->isPost()){
            $phone=$request->param('phone');
            $password=$request->param('password');
            $type=$request->param('type');
            $open_id=$request->param('openId');
            $user=new User();
            return $user->bindUser($phone,$password,$open_id,$type);
        }
        return config('PARAMS_ERROR');
    }

}