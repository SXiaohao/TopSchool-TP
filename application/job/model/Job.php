<?php


namespace app\job\model;


use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\exception\PDOException;
use think\Model;

class Job extends Model
{
    const COUNT_OF_PAGE = 10;

    /**
     * 发布兼职
     * @param $job
     * @return array
     */
    public function addJob($job)
    {

        if ($this->save(['userid' => $job->userid,
                'schoolid' => $job->schoolid,
                'title' => $job->title,
                'units' => $job->units,
                'validtime' => $job->validtime,
                'treatment' => $job->treatment,
                'content' => $job->content,
                'jobstatus' => 1,
                'status' => 1,
                'site' => $job->site,
                'type' => $job->type,
                'addtime' => date('Y-m-d H:i:s', time())]
        )) {
            return ['status' => 200, 'msg' => '发布成功！！'];
        }
        return ['status' => 400, 'msg' => '发布失败！！'];

    }

    /**
     * 查询兼职
     * @param $page
     * @param $id
     * @return array
     */
    public function selJob($page, $id)
    {
        $county = Db::table('ym_school')
            ->where('id', $id)
            ->value('county');
        $totalPages = ceil(Db::table('ym_job')
                ->field('ym_job.*')
                ->where(['county' => $county])
                ->where(['ym_job.status' => 1, 'ym_job.jobstatus' => 1])
                ->count('*') / Job::COUNT_OF_PAGE);

        try {

            if ($id != null) {
                $jobList = Db::table('ym_job')
                    ->field('ym_job.*')
                    ->where(['county' => $county])
                    ->where(['ym_job.status' => 1, 'ym_job.jobstatus' => 1])
                    ->page($page, 10)
                    ->select();

                return [
                    'status' => 200,
                    'msg' => '请过目！',
                    'jobList' => $jobList,
                    'totalPages' => $totalPages,
                ];
            }
            return [
                'status' => 400,
                'msg' => '还没有这个兼职！试试别的吧！',
            ];
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
    }



    /**
     * 删除兼职
     * @param $id
     * @return array
     */
    public function delJob($id)
    {
        try {
            if ($id != null) {
                $del = Db::table('ym_job')->where('id', $id)->delete();
                return [
                    $this->getLastSql(),
                    'status' => 200,
                    'meg' => '删除成功',
                    $del,
                ];
            }
            return [
                'status' => 400,
                'meg' => '操作失误,稍后再试',
            ];
        } catch (PDOException $e) {
        } catch (Exception $e) {
        }

    }

    public function getJobList($page, $type, $county)
    {
        if ($type == "全部") {
            $totalPages = ceil(Db::table('ym_job')
                    ->where(['county' => $county, 'status' => 1])
                    ->count('*') / Job::COUNT_OF_PAGE);
        } else {
            $totalPages = ceil(Db::table('ym_job')
                    ->where(['county' => $county, 'type' => $type, 'status' => 1])
                    ->count('*') / Job::COUNT_OF_PAGE);
        }

        try {
            switch ($type) {
                case "长期兼职":
                    $jobList = Db::table('ym_job')
                        ->where(['county' => $county])
                        ->where(['status' => 1, 'jobstatus' => 1])
                        ->page($page, 10)
                        ->select();

                    return ['status' => 200, 'msg' => '查询成功！！',
                        'jobList' => $jobList, 'totalPages' => $totalPages];

                case "短期兼职":
                    $jobList = Db::table('ym_job')
                        ->where(['county' => $county])
                        ->where(['status' => 1, 'jobstatus' => 1])
                        ->page($page, 10)
                        ->select();

                    return ['status' => 200, 'msg' => '查询成功！！',
                        'jobList' => $jobList, 'totalPages' => $totalPages];

                case "实习兼职":
                    $jobList = Db::table('ym_job')
                        ->where(['county' => $county])
                        ->where(['status' => 1, 'jobstatus' => 1])
                        ->page($page, 10)
                        ->select();

                    return ['status' => 200, 'msg' => '查询成功！！',
                        'jobList' => $jobList, 'totalPages' => $totalPages];

                case "快结兼职":
                    $jobList = Db::table('ym_job')
                        ->where(['county' => $county])
                        ->where(['status' => 1, 'jobstatus' => 1])
                        ->page($page, 10)
                        ->select();

                    return ['status' => 200, 'msg' => '查询成功！！',
                        'jobList' => $jobList, 'totalPages' => $totalPages];

                default:
                    break;
            }

        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
        return ['status' => 400, 'msg' => '查询失败！！' ,var_dump($this->getLastSql())];

    }


}