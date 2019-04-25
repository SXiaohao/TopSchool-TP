<?php


namespace app\common\model;


use think\Db;
use think\Model;

class Search extends Model
{

    /**
     * 搜索
     * @param $title
     * @param $id
     * @return array
     */
    public function searchMess($title, $id)
    {
        try {
            $Job = Db::query("SELECT ym_job.jobtitle,ym_job.id FROM `ym_job` JOIN ym_school ON ym_job.county = ym_school.county WHERE ym_school.id = '$id' AND ym_job.jobtitle LIKE '$title%' ORDER BY addtime DESC");

            $Market = Db::query("SELECT market_name,market_school,market_id FROM `ym_market` JOIN ym_school ON ym_market.market_school = ym_school.title WHERE
    
    ym_school.id = '$id' AND ym_market.market_name LIKE '$title%' ORDER BY ym_market.add_date DESC");

            $User = Db::query("SELECT user_name,user_id FROM `ym_user` WHERE user_name LIKE '$title%'");
            $Confession = Db::query("SELECT content,article_id FROM `ym_confession` JOIN ym_school ON ym_confession.id = ym_school.id WHERE ym_confession.id = '$id'
    AND ym_confession.content LIKE '%$title%'");


            for ($i = 0; $i < count($Confession); $i++) {

                $Confession[$i]["release_time"] = uc_time_ago($Confession[$i]["release_time"]);
                $Confession[$i]["images_list"] = explode(",", $Confession[$i]["images_list"]);
            }
        } catch (\Exception $e) {
        }

        return [
            'job' => $Job,
            'market' => $Market,
            'user' => $User,
            'confession' => $Confession
        ];
    }
}