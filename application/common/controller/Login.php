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
use think\Controller;
use think\Request;


class Login extends Controller
{
    public function login(Request $request){
        $phone = $request->phone;
        $password = $request->password;
        $User = new User();
        $User = $User->findByPhone($phone);
        //如果用户存在
        if($User) {
            if ($User->password === passSalt($password)) {//验证密码
                $User->token = getToken($phone);//获取token
                if($User->save()){
                    $School = new School();
                    $School = $School->getBySchoolId($User->id);
                    return ['status'=>200,'msg'=>'登陆成功','user'=>$User,'school'=>$School];
                }else{
                    return config('SYS_ERROR');
                }
            }else{
                return ['status'=>201,'msg'=>'手机号或密码错误!'];
            }
        } else {
            return ['status'=>202,'msg'=>'您的手机号未注册，请先注册!'];
        }
    }
}