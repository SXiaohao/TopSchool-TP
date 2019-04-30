<?php


namespace app\message\Utils;


use AppConditions;
use IGeTui;
use IGtAppMessage;
use IGtNotificationTemplate;

class pushMessageAll
{
    /**
     *全部推送
     * @param $title
     * @param $text
     * @return mixed|null
     */
    public function pushMessageToAppOfNotification($title, $text)
    {
        require_once '../extend/GETUI/IGt.Push.php';
        $igt = new IGeTui(config('uni_push')["HOST"],
            config('uni_push')["APPKEY"],
            config('uni_push')["MASTERSECRET"]);
        //$template = $this->IGtLinkTemplateDemo($title, $text);
        $template=$this->IGtNotificationTemplateDemo($title, $text);
        //个推信息体
        //基于应用消息体
        $message = new IGtAppMessage();
        $message->set_isOffline(true);
        //离线时间单位为毫秒，例，两个小时离线为3600*1000*2
        $message->set_offlineExpireTime(10 * 60 * 1000);
        $message->set_data($template);
        //$message->setPushTime("201808011537");
        $appIdList = array(config('uni_push')["APPID"]);
        $phoneTypeList = array('ANDROID');

        $cdt = new AppConditions();
        $cdt->addCondition(AppConditions::PHONE_TYPE, $phoneTypeList);

        $message->set_appIdList($appIdList);
        $message->set_conditions($cdt);

        $rep = $igt->pushMessageToApp($message);
        return $rep;
    }

    function IGtNotificationTemplateDemo($title, $text)
    {
        $template = new IGtNotificationTemplate();
        $template->set_appId(config('uni_push')["APPID"]);//应用appid
        $template->set_appkey(config('uni_push')["APPKEY"]);//应用appkey
        $template->set_transmissionType(1);//透传消息类型
        $template->set_transmissionContent("测试离线");//透传内容
        $template->set_title($title);//通知栏标题
        $template->set_text($text);//通知栏内容
        $template->set_logo("");//通知栏logo
        $template->set_isRing(true);//是否响铃
        $template->set_isVibrate(true);//是否震动
        $template->set_isClearable(true);//通知栏是否可清除
        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
        return $template;
    }
}