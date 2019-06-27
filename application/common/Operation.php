<?php

namespace app\common;

use think\facade\{Log, Env};
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * 
 */
class Operation
{
	
	public function getArea($ip)
	{
		$ipfile = Env::get('extend_path').'ip/qqwry.dat';      //获取ip对应地区的信息文件  
	    $iplocation = new \ip\IpLocation($ipfile);  //new IpLocation($ipfile) $ipfile ip对应地区信息文件  
	    $ipresult = $iplocation->getlocation($ip); //根据ip地址获得地区 getlocation("ip地区")  
	    return $ipresult; 
	}

    /**
     * @param int $counts
     * @return string
     * 生成 $counts 个随机字符
     */
    public static function buildRandom($counts = 10)
    {
        $charid = md5(uniqid(rand(), true));
        $uuid   = substr($charid, 0, 3)
            .substr($charid, 8, 3)
            .substr($charid,12, 3)
            .substr($charid,16, 1);
        //.substr($charid,20, 2);
        return $uuid;
    }

    /**
     * @return mixed
     */
    public function getSalt()
    {
        $rand = base64_encode(uniqid().microtime(true).uniqid());
        for($i = 0; $i <= 32; $i++)
        {
            $len        = mt_rand(0, 32);
            $tmp        = $rand{$i};
            $rand{$i}   = $rand{$len};
            $rand{$len} = $tmp;
        }
        $salt = str_replace('+','#', substr(strtoupper($rand), 8, 22));
        return $salt;
    }

    public function test($data = array(),$title = array(),$fname = 'export',$tname= '')
    {
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        //设置工作表标题名称
        $worksheet->setTitle($tname);

        //表头
        //设置单元格内容
        foreach ($title as $key => $value) {
            $worksheet->setCellValueByColumnAndRow($key + 1, 1, $value);
        }
        Log::write($title,'t_arr');

        $row = 2; //第二行开始
        foreach ($data as $item) {
            $column = 1;
            foreach ($item as $value) {
                $worksheet->setCellValueByColumnAndRow($column, $row, $value);
                $column++;
            }
            $row++;
        }

        # 保存为xlsx
       $filename = $fname.'.xlsx';
     //  Log::write(__DIR__ . DIRECTORY_SEPARATOR.'../../public/export/','dir');
       $filename2 = '/www/wwwroot/tp5/public/export/'.$filename;
       $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
       $writer->save($filename2);
      
       // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
       // header('Content-Disposition: attachment;filename="'.$filename.'"');
       // header('Cache-Control: max-age=0');
       // $writer->save('php://output');(url, method, filedir, filename)
		$res['url'] = $filename2;
        $res['method'] = 'post';
        $res['filedir'] = '/www/wwwroot/tp5/public/export/';
        $res['filename'] = $filename;
        return $res;
    }
}