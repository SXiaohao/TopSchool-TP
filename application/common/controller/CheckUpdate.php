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
        return ["version" => 1.1,
            "packageUrl" => 'https://000484.apk',
            "content" => '更新内容:\n.修复上传房源时图片重复错乱问题\n.修复启动app时启动图变形问题\n.优化部分性能问题\n.新增功能1.新增功能2.新增功能3.新增功能3.新增功能3.新增功能3.新增功能3.新增功能3.新增功能3',
            "contentAlign" => 'left',
            "cancel" => '取消',
            "cancelColor" => '#007fff'
        ];
    }
}