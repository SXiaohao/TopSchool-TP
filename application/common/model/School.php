<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/17
 * Time: 0:25
 */

namespace app\common\model;

use think\Model;
class School extends Model
{
    /**
     * * 查询学校id是否存在,存在返回当前对象,不存在返回null
     * @param string $SchoolId
     * @return array|\PDOStatement|string|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getBySchoolId($SchoolId){
        return $this->where('id',$SchoolId)->find();
    }

    /*
     * 插入一条学校信息
     * @param Model $school
     * @return bool
     */
    public function getSchool($school){
        if($this->getBySchoolId($school->id) === null){
            $this->id = $school->id;
            $this->title = $school->title;
            $this->addr = $school->addr;
            return $this->save();
        }
    }
}