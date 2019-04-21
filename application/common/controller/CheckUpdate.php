<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/24
 * Time: 0:06
 */

namespace app\common\controller;


use think\Controller;

class CheckUpdate extends Controller
{
    public function update()
    {
        return ["version" => "1.1.5",
            "name" => "Top校园",
            "info" => "1.完善超市注册系统\n2.新增超市管理模块",
            "downloadLink" => "http://icloud.9ykm.cn/cn.ymkj.test7.apk",
            "iosLink" => "#",
            "packgeSize" => "6212573",
            "type" => "alpha"
        ];
    }
}