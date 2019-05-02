<?php


namespace app\message\Utils;


use DictionaryAlertMsg;
use Exception;
use IGtAPNPayload;
use IGtLinkTemplate;
use IGtMultiMedia;
use IGtNotificationTemplate;
use IGtNotyPopLoadTemplate;
use IGtTransmissionTemplate;
use MediaType;
use SimpleAlertMsg;

class template
{
//所有推送接口均支持四个消息模板，依次为通知弹框下载模板，通知链接模板，通知透传模板，透传模板
//注：IOS离线推送需通过APN进行转发，需填写pushInfo字段，目前仅不支持通知弹框下载功能

    /**
     * 通知弹框下载模板
     * @return IGtNotyPopLoadTemplate
     */
    public static function IGtNotyPopLoadTemplateDemo()
    {
        $template = new IGtNotyPopLoadTemplate();

        $template->set_appId(APPID);//应用appid
        $template->set_appkey(APPKEY);//应用appkey
        //通知栏
        $template->set_notyTitle("个推");//通知栏标题
        $template->set_notyContent("个推最新版点击下载");//通知栏内容
        $template->set_notyIcon("");//通知栏logo
        $template->set_isBelled(true);//是否响铃
        $template->set_isVibrationed(true);//是否震动
        $template->set_isCleared(true);//通知栏是否可清除
        //弹框
        $template->set_popTitle("弹框标题");//弹框标题
        $template->set_popContent("弹框内容");//弹框内容
        $template->set_popImage("");//弹框图片
        $template->set_popButton1("下载");//左键
        $template->set_popButton2("取消");//右键
        //下载
        $template->set_loadIcon("");//弹框图片
        $template->set_loadTitle("地震速报下载");
        $template->set_loadUrl("http://dizhensubao.igexin.com/dl/com.ceic.apk");
        $template->set_isAutoInstall(false);
        $template->set_isActived(true);
        //$template->set_notifyStyle(0);
        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息

        return $template;
    }

    /**
     * 通知链接模板
     * @return IGtLinkTemplate
     */
public static function IGtLinkTemplateDemo()
    {
        require_once '../extend/GETUI/igetui/template/IGt.BaseTemplate.php';
        $template = new IGtLinkTemplate();

        $template->set_appId(config('uni_push')["APPID"]);//应用appid
        $template->set_appkey(config('uni_push')["APPKEY"]);//应用appkey
        $template->set_title("请输入通知标题1");//通知栏标题
        $template->set_text("请输入通知内容");//通知栏内容
        $template->set_logo("");//通知栏logo
        $template->set_isRing(true);//是否响铃
        $template->set_isVibrate(true);//是否震动
        $template->set_isClearable(true);//通知栏是否可清除

        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
        return $template;
    }

    /**
     * 通知透传模板
     * @param $title
     * @param $text
     * @return IGtNotificationTemplate
     */
    public static function IGtNotificationTemplateDemo($title, $text)
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

    /**
     * 透传模板
     * @return IGtTransmissionTemplate
     * @throws Exception
     */
    public static function IGtTransmissionTemplateDemo()
    {
        require_once '../extend/GETUI/igetui/template/IGt.BaseTemplate.php';
        require_once '../extend/GETUI/igetui/IGt.MultiMedia.php';

        $template = new IGtTransmissionTemplate();
        $template->set_appId(config('uni_push')["APPID"]);//应用appid
        $template->set_appkey(config('uni_push')["APPKEY"]);//应用appkey
        $template->set_transmissionType(1);//透传消息类型
        $template->set_transmissionContent("测试离线ddd");//透传内容
        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
        //APN简单推送
        $apn = new IGtAPNPayload();
        $alertmsg = new SimpleAlertMsg();
        $alertmsg->alertMsg = "abcdefg3";
        $apn->alertMsg = $alertmsg;
        $apn->badge = 2;
        $apn->sound = "";
        $apn->add_customMsg("payload", "payload");
        $apn->contentAvailable = 1;
        $apn->category = "ACTIONABLE";
        $template->set_apnInfo($apn);
        //VOIP推送
//    $voip = new VOIPPayload();
//    $voip->setVoIPPayload("新浪");
//    $template->set_apnInfo($voip);


        //第三方厂商推送透传消息带通知处理
//    $notify = new IGtNotify();
////    $notify -> set_payload("透传测试内容");
//    $notify -> set_title("透传通知标题");
//    $notify -> set_content("透传通知内容");
//    $notify->set_url("https://www.baidu.com");
//    $notify->set_type(NotifyInfo_Type::_url);
//    $template -> set3rdNotifyInfo($notify);
        //APN高级推送
        $apn = new IGtAPNPayload();
        $alertmsg = new DictionaryAlertMsg();
        $alertmsg->body = "body";
        $alertmsg->actionLocKey = "ActionLockey";
        $alertmsg->locKey = "LocKey";
        $alertmsg->locArgs = array("locargs");
        $alertmsg->launchImage = "launchimage";
//        IOS8.2 支持
        $alertmsg->title = "Title";
        $alertmsg->titleLocKey = "TitleLocKey";
        $alertmsg->titleLocArgs = array("TitleLocArg");

        $apn->alertMsg = $alertmsg;
        $apn->badge = 7;
        $apn->sound = "";
        $apn->add_customMsg("payload", "payload");
        $apn->contentAvailable = 1;
        $apn->category = "ACTIONABLE";
//
////    IOS多媒体消息处理
        $media = new IGtMultiMedia();
        $media->set_url("http://docs.getui.com/start/img/pushapp_android.png");
        $media->set_onlywifi(false);
        $media->set_type(MediaType::pic);
        $medias = array();
        $medias[] = $media;
        $apn->set_multiMedias($medias);
        $template->set_apnInfo($apn);
        return $template;
    }

}