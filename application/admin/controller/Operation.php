<?php
namespace app\admin\controller;

use think\Controller;
use think\facade\Log;
use think\facade\Session;
use think\Db;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Operation extends Controller
{
	public function test()
    {
    	
       // $data = array(0 => array(0 => 1, 1 => 'alex1', 2 => 1,), 1 => array(0 => 2, 1 => 'alex2', 2 => 2,), 2 => array(0 => 3, 1 => 'alex3', 2 => 1,), 3 => array(0 => 4, 1 => 'alex4', 2 => 2,), 4 => array(0 => 5, 1 => 'alex5', 2 => 1,), 5 => array(0 => 6, 1 => 'alex6', 2 => 2,));
		$data = Session::get('exdata');
        $title = ['id', 'name', 'sex'];


        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        //设置工作表标题名称
        $worksheet->setTitle('测试Excel');

        //表头
        //设置单元格内容
        foreach ($title as $key => $value) {
            $worksheet->setCellValueByColumnAndRow($key + 1, 1, $value);
        }
		Log::write($data, 'exdataT');
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
       $filename = '测试Excel.xlsx';
       $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
       $writer->save($filename);
      
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');

    }
  
    // 下载
    public function download()
    {
    	// 
       // $filename = $this->request->post('file_name');
      $filename = $this->request->param('filename');
     // Log::write($hh,'hhj');
        Log::write($filename, 'filename');
     // return $filename;
        return download($filename,'hh.xlsx');
    }
}