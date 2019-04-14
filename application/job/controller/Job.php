<?php


namespace app\job\controller;


use think\Controller;
use think\Request;

class Job extends Controller
{
    public function addJob(Request $request)
    {
        if ($request->isPost()) {
            $Job = new \app\job\model\Job();
            return $Job->addJob($request);
        }
        return config('PARAMS_ERROR');
    }

    public function selJob(Request $request){
        if ($request->isPost()) {
            $page = $request->param('page');
            $id = $request->param('id');
            $Job = new \app\job\model\Job();
            return $Job->selJob($page,$id);
        }
        return config('PARAMS_ERROR');
    }

    /**
     * 分页查询
     * @param Request $request
     * @return array|mixed
     */
    public function likeJob(Request $request){
        if ($request->isPost()) {
            $page = $request->param('page');
            $jobtitle = $request->param('jobtitle');
            $id = $request->param('id');
            $Job = new \app\job\model\Job();
            return $Job->likeJob($page,$jobtitle,$id);
        }
        return config('PARAMS_ERROR');
    }

    public function delJob(Request $request){
        if ($request->isPost()) {
            $Job = new \app\job\model\Job();
            return $Job->delJob($request->param('id'));
        }
        return config('PARAMS_ERROR');
    }


    /**
     * 更改信息
     * @param Request $request
     * @return mixed
     */
    public function updateJob(Request $request){
        if($request->isPost()){
            $id = $request->param('id');
            $jobtitle = $request->param('jobtitle');
            $schoolid = $request->param('schoolid');
            $units = $request->param('units');
            $validtime = $request->param('validatime');
            $site = $request->param('site');
            $treatment = $request->param('treatment');
            $content = $request ->param('content');
            $Job = new \app\job\model\Job();
            return $this->updateJob($id, $jobtitle, $schoolid, $units, $validtime, $site, $treatment, $content);
        }
        return config('PARAMS_ERROR');
    }
}