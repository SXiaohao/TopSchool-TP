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
        return ["version" => "1.2.3",
            "name" => "Pure-updater",
            "number" => "1.0.1",
            "info" => "1.此版本用于测试自动更新\n2.请求数据来自Easy Mock\n3.请更换成自己的api地址",
            "downloadLink" => "https://icloud.9ykm.cn/com.ymkj.jiuyuan.apk",
            "iosLink" => "#",
            "packgeSize" => "6212573",
            "type" => "alpha"
        ];
    }
}