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



    public function getJobList(Request $request){
        if($request->isPost()){
            $page = $request->param('page');
            $type = $request->param('type');
            $id = $request->param('id');
            $Job = new \app\job\model\Job();
            return $Job->getJobList($page,$type,$id);
        }
        return config('PARAMS_ERROR');
    }
}