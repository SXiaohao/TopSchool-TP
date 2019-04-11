<?php


namespace app\message\controller;


use think\Controller;

class Push extends Controller
{
    public function chat()
    {
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, 'http://localhost/confession/card/index?page=1');
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
    }
}