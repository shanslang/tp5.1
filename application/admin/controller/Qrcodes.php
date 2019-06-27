<?php

namespace app\admin\controller;

use think\Controller;

//use Endroid\QrCode\QrCode;
//use Endroid\QrCode\ErrorCorrectionLevel;
//use Endroid\QrCode\LabelAlignment;
use app\common\QrcodeServer;

class Qrcodes extends Controller{
//    public function index()
//    {
//        $qrCode = new QrCode('http://con.ee591.com');
//
//        header('Content-Type: '.$qrCode->getContentType());
//        echo $qrCode->writeString();
//        exit;
//    }

        public function index()
        {
            /**
             * 直接输出二维码 + 生成二维码图片文件
             */
            // 自定义二维码配置
            $config = [
                'title'         => true,
                'title_content' => '',
                'logo'          => false,
                'logo_url'      => '/www/wwwroot/tp5/public/static/img/pic_by.png',
                'logo_size'     => 80,
            ];

            // 直接输出
            $qr_url = 'http://down.0925pi.cn';

            $qr_code = new QrcodeServer($config);
            $qr_img = $qr_code->createServer($qr_url);
            echo $qr_img;

            // 写入文件
            $qr_url = '这是个测试二维码';
            $file_name = '/www/wwwroot/tp5/public/static/img/qrcode';  // 定义保存目录

            $config['file_name'] = $file_name;
            $config['generate']  = 'writefile';

            $qr_code = new QrcodeServer($config);
            $rs = $qr_code->createServer($qr_url);
            print_r($rs);

            exit;
        }


}