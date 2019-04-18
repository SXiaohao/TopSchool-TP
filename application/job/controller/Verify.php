<?php


namespace app\job\controller;


use app\job\model\JobVerify;
use think\Controller;
use think\Request;

class Verify extends Controller
{
    /**
     * 上传身份证
     * @param Request $request
     * @return array|mixed
     */
    public function upload(Request $request)
    {
        if ($request->isPost()) {
            $card_front = $request->file('card_front');
            $card_back=$request->file('card_back');
            $jobVerify = new JobVerify();
            return $jobVerify->getImgPath($card_front,$card_back);
        }
        return config('PARAMS_ERROR');
    }

    /**
     * 验证身份信息
     * @param Request $request
     * @return array|mixed
     */
    public function insertInfo(Request $request)
    {
        if ($request->isPost()) {
            $jobVerify = new JobVerify();
            return $jobVerify->insertInfo($request);
        }
        return config('PARAMS_ERROR');
    }

    /**
     * 查询注册状态
     * @param Request $request
     * @return array|mixed
     */
    public function jobMerchant(Request $request){
        if ($request->isPost()) {
            $jobVerify = new JobVerify();
            return $jobVerify->jobMerchant($request->param('user_id'));
        }
        return config('PARAMS_ERROR');
    }
}