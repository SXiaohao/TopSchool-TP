<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/23
 * Time: 13:56
 */

namespace app\common\model;


use think\Db;
use think\Model;

class thumbsUp extends Model
{

    public function addThumbsUp($phone, $thumbs_up_type, $type_id){
        if (Db::name('thumbsUp')->insert(['phone'=>$phone,'thumbs_up_type'=>$thumbs_up_type,'type_id'=>$type_id])==1){
         return ['status'=>200,'msg'=>'点赞成功！！'];
        }
        return ['status'=>400,'msg'=>'点赞失败！！'];
    }

}