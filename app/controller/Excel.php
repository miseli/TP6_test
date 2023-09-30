<?php
declare (strict_types = 1);

namespace app\controller;

use think\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer;
use PhpOffice\PhpSpreadsheet\Reader;
use PhpOffice\PhpSpreadsheet\Style\Borders;

class Excel
{

    function write1($file = 'C:\\Users\\Cube\\AppData\\Local\\Temp\\123.xlsx'){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');

        $writer = new Writer\Xlsx($spreadsheet);
        $writer->save($file);
        return $file;
    }

    function read1($file){
        $reader = new Reader\Xls();
        $spreadsheet = $reader->load($file);
        $lines = $spreadsheet->getActiveSheet()->toArray();
        return $lines;
    }

    public function get()
    {
        $ts = intval(_GET('ts'));
        $riqi = date("Y 年 m 月 d 日", $ts);

        $dict = ['','B11','B12','D3','D4','D5','D6','D7','D8','D9','D10','D11','D12'];
        $templatefile = realpath('.') . '/公司风险研判签到表模板.xlsx';
        $reader = new Reader\Xlsx();
        $spreadsheet = $reader->load($templatefile);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', '乙烯分公司                     会议时间： '.$riqi);
        // // 生成数据
        // $sheet->fromArray($data, null, "A2");
        // // 样式设置
        // $sheet->getDefaultColumnDimension()->setWidth(12);
        $without = _GET('n');
        foreach ($without as $item) {
            // 填写内容
            $sheet->setCellValue($dict[$item], '无作业');
            // 打斜杠
            // $sheet->getStyle($dict[$item])->getBorders()->setDiagonalDirection(Borders::DIAGONAL_UP);
            // $sheet->getStyle($dict[$item])->getBorders()->getDiagonal()->setBorderStyle('thin');
        }

        $filename = _GET('outname');
        \app\third_lib\ImportExportSetting::export_set($filename, 'xlsx');
        // 调用方法执行下载
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        // 数据流
        $writer->save("php://output");
    }


    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return 'Excel控制器';
        //
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
