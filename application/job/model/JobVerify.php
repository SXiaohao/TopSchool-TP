<?php


namespace app\job\model;


use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\Model;

class JobVerify extends Model
{
    /**
     * 获取图片路径  身份证正反面
     * @param $card_front
     * @param $card_back
     * @return array
     */
    public function getImgPath($card_front, $card_back)
{
    if ($card_front != null) {
        return $this->uploadImg($card_front, 'card_front');
    } elseif ($card_back != null) {
        return $this->uploadImg($card_back, 'card_back');
    }
    return ['status' => 400,
        'msg' => '上传失败！！'];
}

    /**
     * 上传图片
     * @param $file
     * @param $type
     * @return array
     */
    private function uploadImg($file, $type)
    {
        //上传图片并返回访问路径
        $info = $file->validate(['ext', 'jpg,jpeg,png,gif'])->move('../public/static/JobVerify/' . $type);
        if ($info) {
            $getSaveName = str_replace("\\", "/", $info->getSaveName());
            $imagePath = config('local_path') . "/static/JobVerify/" . $type . "/" . $getSaveName;
        } else {
            return ['status' => 400,
                'msg' => $file->getError()];
        }

        return ['status' => 200,
            'msg' => '上传成功！',
            'imagePath' => $imagePath];
    }

    /**
     * 上传注册信息
     * @param $request
     * @return array|mixed
     */
    public function insertInfo($request)
    {
        if (!checkToken($request->token, $request->phone)) {
            return config('NOT_SUPPORTED');
        }

        $status = Db::table('ym_job_verify')
            ->insert(['user_id' => $request->user_id,
                'card_front' => $request->card_front,
                'card_back' => $request->card_back,
                'verify_name' => $request->verify_name,
                'id_number' => $request->id_number,
                'phone' =>$request->phone]);

        if ($status) {
            try {
                $status = Db::table('ym_user')->where(['user_id' => $request->user_id])->update(['job_merchant' => 2]);
                if ($status == 1) {
                    return ['status' => 200, 'msg' => '注册成功，等待审核！'];
                }
                var_dump($this->getLastSql());
                return ['status' => 400, 'msg' => '更新状态失败！！'];
            } catch (PDOException $e) {
            } catch (Exception $e) {
            }

            return ['status' => 400, 'msg' => '更新状态失败！！'];
        }
        return ['status' => 400, 'msg' => '注册失败！！'];
    }


    /**
     * 查询注册状态
     * @param $user_id
     * @return array
     */
    public function jobMerchant($user_id){

        $jobmerchant = Db::query("SELECT job_merchant FROM `ym_user` WHERE user_id = $user_id");
        $jobmerchant = (int)($jobmerchant);
        var_dump($this->getLastSql());
        if($jobmerchant == 1){
            return ['status' => 200, 'msg' => '恭喜您注册成功,！！'];
        }elseif ($jobmerchant == 2){
            return ['status' => 200, 'msg' => '审核中,待会再来看看'];
        }elseif ($jobmerchant == null){
            return ['status' => 400, 'msg' => '先注册才能发兼职!'];
        }else{
            return ['status' => 400, 'msg' => '先注册才能发兼职!'];
        }
    }
}