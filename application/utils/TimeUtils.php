<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/18
 * Time: 14:35
 */

namespace app\utils;


class TimeUtils
{
//时间格式化（时间戳）
    public static function uc_time_ago($ptime)
    {
        date_default_timezone_set('PRC');
        $ptime = strtotime($ptime);
        $etime = time() - $ptime;
        switch ($etime) {
            case $etime <= 24 * 60 * 60:
                $msg = date('Ymd', $ptime) == date('Ymd', time()) ? '今天 ' . date('H:i', $ptime) : '昨天 ' . date('H:i', $ptime);
                break;
            case $etime > 24 * 60 * 60 && $etime <= 2 * 24 * 60 * 60:
                $msg = date('Ymd', $ptime) + 1 == date('Ymd', time()) ? '昨天 ' . date('H:i', $ptime) : '前天 ' . date('H:i', $ptime);
                break;
            default:
                $msg = date('m月d日 ', $ptime);
        }
        return $msg;
    }
}