<?php


namespace app\common\controller;

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode as QrCodeExt;
use think\Controller;
use Endroid\QrCode\QrCode;

class Qr extends Controller
{
    /**
     * 生成二维码
     * @param string $text [字符]
     * @param bool $is_save
     * @param int $pid
     * @return void [type]          [description]
     */
    public function create($text = 'https://packagist.org/packages/endroid/qr-code?pid=1000',
                           $is_save = true, $pid = 1)
    {
        $qrCode = new QrCodeExt($text);
        $qrCode->setSize(300);
        // Set advanced options
        $qrCode->setWriterByName('png');
        $qrCode->setMargin(10);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
        // $qrCode->setLabel('Scan the code', 16, __DIR__ . '/../assets/fonts/noto_sans.otf', LabelAlignment::CENTER);
        // $qrCode->setLogoPath(__DIR__ . '/../assets/images/symfony.png');
        $qrCode->setLogoSize(150, 200);
        $qrCode->setRoundBlockSize(true);
        $qrCode->setValidateResult(false);
        $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);
        // Directly output the Qr code
       // header('Content-Type: ' . $qrCode->getContentType());
        if ($is_save) {

            // Save it to a file
            $qrCode->writeFile( '../public/static/qrcode/' . $pid . '.png');
        }

        die($qrCode->writeString());
    }

}