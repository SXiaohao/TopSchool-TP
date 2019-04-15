<?php


namespace app\market\model;


use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\Model;

class MarketVerify extends Model
{
    /**
     * 获取图片路径
     * @param $card_front
     * @param $card_back
     * @param $student_card
     * @return array
     */
    public function getImgPath($card_front, $card_back, $student_card)
    {
        if ($card_front != null) {
            return $this->uploadImg($card_front, 'card_front');
        } elseif ($card_back != null) {
            return $this->uploadImg($card_back, 'card_back');
        } elseif ($student_card != null) {
            return $this->uploadImg($student_card, 'student_card');
        }

        return ['status' => 400,
            'msg' => '上传失败！！'];
    }

    /**
     * 上传注册信息
     * @param $request
     * @return array
     */
    public function insertInfo($request)
    {
        if (!checkToken($request->token, $request->phone)) {
            return config('NOT_SUPPORTED');
        }

        $status = Db::table('ym_market_verify')
            ->insert(['user_id' => $request->user_id,
                'card_front' => $request->card_front,
                'card_back' => $request->card_back,
                'student_card' => $request->student_card,
                'verify_name' => $request->verify_name,
                'id_number' => $request->id_number]);

        if ($status) {
            try {
                $status = Db::table('ym_user')->where(['user_id' => $request->user_id])->update(['merchant' => 2]);
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
     * 上传图片
     * @param $file
     * @param $type
     * @return array
     */
    private function uploadImg($file, $type)
    {
        //上传图片并返回访问路径
        $info = $file->validate(['ext', 'jpg,jpeg,png,gif'])->move('../public/static/market/' .
            $type);
        if ($info) {
            $getSaveName = str_replace("\\", "/", $info->getSaveName());
            $imagePath = config('local_path') . "/static/market/" . $type . "/" . $getSaveName;
        } else {
            return ['status' => 400,
                'msg' => $file->getError()];
        }

        return ['status' => 200,
            'msg' => '上传成功！',
            'imagePath' => $imagePath];
    }
}