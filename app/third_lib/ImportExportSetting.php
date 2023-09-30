<?php

// http://localhost:8010/tp6/public/excel/get?n[]=1&n[]=2&n[]=3&n[]=4&n[]=5&n[]=6&n[]=7&n[]=9&n[]=10&n[]=11&n[]=12&outname=20231005&ts=1696464000
// https://cube123.cn/tp6/public/index.php/excel/get?n[]=1&n[]=2&n[]=3&n[]=4&n[]=5&n[]=6&n[]=7&n[]=9&n[]=10&n[]=11&n[]=12&outname=20231005&ts=1696464000

// http://localhost:8010/tp6/public/index.php/word/exportWord?workdata[]=0&workdata[]=4&workdata[]=0&workdata[]=1&workdata[]=0&workdata[]=3&workdata[]=1&workdata[]=0&workdata[]=5&workdata[]=0&workdata[]=3&riqi=1695859200
// https://cube123.cn/tp6/public/index.php/word/exportWord?workdata[]=0&workdata[]=4&workdata[]=0&workdata[]=1&workdata[]=0&workdata[]=3&workdata[]=1&workdata[]=0&workdata[]=5&workdata[]=0&workdata[]=3&riqi=1695859200

/**
 * @Author: Cube
 * @Date:   2023-09-29 23:01:30
 * @Last Modified by:   Cube
 * @Last Modified time: 2023-09-30 02:26:14
 */
namespace app\third_lib;

use think\exception\ValidateException;
use think\facade\Filesystem;

/**
 * Excel表格的导入导出类库
 */

class ImportExportSetting
{

    /**
     * @param string $filename
     * @return array|string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public static function importExcel($filename = "")
    {
        $file[] = $filename;

        try {
            // 验证文件大小，名称等是否正确
            validate(['file' => 'filesize:51200|fileExt:xls,xlsx'])
                ->check($file);
            // 将文件保存到本地
            $savename = Filesystem::disk('public')->putFile('file', $file[0]);
            // 截取后缀
            $fileExtendName = substr(strrchr($savename, '.'), 1);
            // 有Xls和Xlsx格式两种
            if ($fileExtendName == 'xlsx') {
                $objReader = IOFactory::createReader('Xlsx');
            } else {
                $objReader = IOFactory::createReader('Xls');
            }
            // 设置文件为只读
            $objReader->setReadDataOnly(TRUE);
            // 读取文件，tp6默认上传的文件，在runtime的相应目录下，可根据实际情况自己更改
            $objPHPExcel = $objReader->load(public_path() . 'admin/' . $savename);
            //excel中的第一张sheet
            $sheet = $objPHPExcel->getSheet(0);
            // 取得总行数
            $highestRow = $sheet->getHighestRow();
            // 取得总列数
            $highestColumn = $sheet->getHighestColumn();
            Coordinate::columnIndexFromString($highestColumn);
            $lines = $highestRow - 1;
            if ($lines <= 0) {
                return "数据为空数组";
            }
            // 直接取出excle中的数据
            $data = $objPHPExcel->getActiveSheet()->toArray();
            // 删除第一个元素（表头）
            array_shift($data);
            //删除文件
            unlink(public_path() . 'admin/' . $savename);
            // 返回结果
            return $data;
        } catch (ValidateException $e) {
            return $e->getMessage();
        }
    }

    // 导出
    public static function export_set($fileName, $suffix)
    {
        // 设置返回头
        switch ($suffix) {
            case 'xlsx':
                header("Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
                break;
            case 'xls':
                header("Content-Type:application/vnd.ms-excel");
                break;
            case 'doc':
                header("Content-Type: application/msword");
                break;
            case 'docx':
                header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
                break;
            default:
                break;
        }
        $type = ucfirst($suffix);

        ob_end_clean();//清楚缓存区
        // 激活浏览器窗口
        header("Content-Disposition:attachment;filename=$fileName.$suffix");
        //缓存控制
        header("Cache-Control:max-age=0");
    }
}

