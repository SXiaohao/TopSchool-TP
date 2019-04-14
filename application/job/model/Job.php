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
                'addtime' => date('Y-m-d H:i:s', time())]
        )) {
            return ['status' => 200, 'msg' => '发布成功！！'];
        }
        return ['status' => 400, 'msg' => '发布失败！！'];

    }

    public function selJob($page,$id)
    {
        $county=Db::table('ym_school')
            ->where('id',$id)
            ->value('county');
        $totalPages = ceil(Db::table('ym_job')
                ->field('ym_job.*')
                ->where(['county' => $county])
                ->where(['ym_job.status' => 1, 'ym_job.jobstatus' => 1])
                ->count('*') / Job::COUNT_OF_PAGE);

        try {

            if ($id!=null) {
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
     * 分页查询
     * @param $page
     * @param $jobtitle
     * @param $id
     * @return array
     */
    public function likeJob($page, $jobtitle, $id)
    {

        $totalPages = ceil(Db::table('ym_job')
                ->join('ym_school', 'ym_school.id = ym_job.schoolid')
                ->where(['ym_school.id' => $id, 'ym_job.jobtitle' => $jobtitle])
                ->where(['ym_job.status' => 1, 'ym_job.jobstatus' => 1])
                ->count('*') / Job::COUNT_OF_PAGE);

        try {
            if ($jobtitle != null) {
                $jobList = Db::table('ym_job')
                    ->join('ym_school', 'ym_school.id = ym_job.schoolid')
                    ->where(['ym_school.id' => $id, 'ym_job.jobtitle' => $jobtitle])
                    ->where(['ym_job.status' => 1, 'ym_job.jobstatus' => 1])
                    ->page($page, 10)
                    ->select();
                $this->getLastSql();
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
     * 更改数据
     * @param $id
     * @param $jobtitle
     * @param $schoolid
     * @param $units
     * @param $validtime
     * @param $site
     * @param $treatment
     * @param $content
     * @return array
     * @throws Exception
     * @throws PDOException
     */
    public function updateJob($id, $jobtitle, $schoolid, $units, $validtime, $site, $treatment, $content)
    {
        $update = Db::table('think_user')
            ->where('id', $id)
            ->update(['jobtitle' => $jobtitle, 'units' => $units, 'validtime' => $validtime, 'site' => $site, 'treatment' => $treatment, 'content' => $content]);
        return [
            $this->getLastSql(),
            'status' => 200,
            'meg' => '更新成功',
            $update,
        ];
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
                'meg' => '稍后再试',
            ];
        } catch (PDOException $e) {
        } catch (Exception $e) {
        }

    }
}