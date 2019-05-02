<?php


namespace app\message\Utils;


use AppConditions;
use IGeTui;
use IGtAppMessage;
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
        $template=template::IGtNotificationTemplateDemo($title, $text);

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


}