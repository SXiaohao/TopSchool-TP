<?php


namespace app\market\model;


use think\Model;

class MarketVerify extends Model
{
    public function upload($type, $file)
    {
        switch ($type) {
            case "":
                return $this->uploadImg($file, 'card_front');

            case "":
                return $this->uploadImg($file, 'card_back');

            case "":
                return $this->uploadImg($file, 'student_card');

        }
        return ['status' => 400,
            'msg' => '上传失败！！'];
    }

    private function uploadImg($file, $type)
    {
        //上传图片并返回访问路径
        $info = $file->validate(['ext', 'jpg,jpeg,png,gif'])->move('../public/static/' . $type);
        if ($info) {
            $getSaveName = str_replace("\\", "/", $info->getSaveName());
            $imagePath = config('local_path') . "/static/" . $type . "/" . $getSaveName;
        } else {
            return ['status' => 400,
                'msg' => $file->getError()];
        }

        return ['status' => 200,
            'msg' => '上传成功！',
            'imagePath' => $imagePath];
    }
}