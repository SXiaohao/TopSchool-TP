<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/16
 * Time: 21:40
 */

namespace app\confession\model;


use think\Model;

class User extends Model
{
    public function select(){
        $User=new User();
       return $User->where('user_id',3)->find();
    }

}