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
        return ["version" => "1.1.8",
            "name" => "Top校园",
            "info" => "1.美化部分页面\n2.创作最完美的logo\n3.增加兼职详情页面",
            "downloadLink" => "http://icloud.9ykm.cn/cn.ymkj.test7.apk",
            "iosLink" => "#",
            "packgeSize" => "6212573",
            "type" => "alpha"
        ];
    }
}