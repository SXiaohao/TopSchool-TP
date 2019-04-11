<?php


namespace app\message\model;


use Exception;
use IGtNotify;
use IGtTransmissionTemplate;
use NotifyInfo_Type;

class Push
{
    /**
     * @throws Exception
     */
    public function messagePush()
    {
        $payload = '{"title":"测试标题","content":"测试内容","payload":"test"}';
        $intent = 'intent:#Intent;action=android.intent.action.oppopush;launchFlags=0x14000000;component=io.dcloud.HBuilder/io.dcloud.PandoraEntry;S.UP-OL-SU=true;S.title=测试标题;S.content=测试内容;S.payload=test;end';

        $template = new IGtTransmissionTemplate();//使用透传消息模板
        //应用appid
        $template->set_appId(APPID);
        $template->set_appkey(APPKEY);//应用appkey
        $template->set_transmissionType(2);//透传消息类型
        $template->set_transmissionContent($payload);//消息内容

        $notify = new IGtNotify();
        $notify->set_title('测试标题');
        $notify->set_content('测试内容');

        $notify->set_intent($intent);
        $notify->set_type(NotifyInfo_type::_intent);

        $template->set3rdNotifyInfo($notify);
    }
}