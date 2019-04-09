<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/17
 * Time: 0:41
 */

namespace app\common\controller;

use app\common\model\Miaodi;
use app\common\model\User;
use think\Controller;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\facade\Cache;
use think\Image;
use think\Request;
use think\response\Json;

class Register extends Controller
{

    /**
     * 发送验证码
     * @param Request $request
     * @return Json
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public function sendVCode(Request $request)
    {
        $User = new User();
        if ($User->findByPhone($request->phone)) {
            return json(['status' => 201, 'msg' => '该账号已注册,请直接登录!']);
        } else {
            $code = mt_rand(100000, 999999);
            $str = "【浩创空间】您的验证码为{1}，请于{2}分钟内正确输入，如非本人操作，请忽略此短信。";
            $md = new Miaodi();
            $result = $md->sendSMS($request->phone, $str, [$code, '5']);
            //$result = sendSms('SMS_159840024', $request->phone, $code);
            if ($result) {
                cache($request->phone, $code, 600);//设置缓存 10分钟有效
                return json(['status' => 200, 'msg' => '验证码发送成功,请注意查收！']);
            } else {
                return json(['status' => 400, 'msg' => $result]);
            }
        }
    }

    //验证验证码
    public function verifyVCode(Request $request)
    {
        if ($request->isGet()) {
            return config('PARAMS_ERROR');
        }
        $code = cache($request->phone);
        if ($code) {
            if ($code === $request->vCode) {
                return config('SUCCESS');
            } else {
                return json(['status' => 201, 'msg' => '验证码错误，请输入正确的验证码']);
            }
        } else {
            return json(['status' => 202, 'msg' => '验证码已失效，请重新获取']);
        }
    }

    //上传头像
    public function uploadAvatar(Request $request)
    {
        if ($request->isGet()) {
            return config('PARAMS_ERROR');
        }
        $file = $request->file('avatar');
        $info = $file->rule('uniqid')->validate(['ext' => 'jpg,jpeg,png'])->move('../public/static/avatar/');
        if ($info) {
            //成功上传后获取上传信息 $url输出 /static/avatar/5c8de9a33f1ad.png
            $url = '/static/avatar/' . $info->getSaveName();
            $image = Image::open($file);
            $width = $image->width();
            $height = $image->height();
            if ($width < $height) {
                $height = $width;
            }
            $image->thumb($height, $height, Image::THUMB_CENTER)->save('../public' . $url, null, 70);
            return json(['status' => 200, 'msg' => '上传成功', 'url' => $url]);
        } else {
            return json(['status' => 202, 'msg' => $file->getError()]);// 上传失败获取错误信息
        }
    }

    //注册
    public function register(Request $request)
    {
        if ($request->isGet()) {
            return config('PARAMS_ERROR');
        }
        $User = new User();
        if ($User->register($request)) {
            $phone = $request->param('phone');
            $token = getToken($phone);//获取token
            Cache::set($phone, $token);
            $User = $User->findByPhone($phone);
            return json(['status' => 200, 'msg' => '注册成功', 'user' => $User, 'token' => $token]);
        } else {
            return config('SYS_ERROR');
        }
    }
}