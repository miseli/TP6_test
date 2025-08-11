<?php
declare (strict_types = 1);

namespace app\controller;

use think\facade\View;
use think\Request;
use think\Response;
use app\BaseController;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer;
use PhpOffice\PhpSpreadsheet\Reader;
use PhpOffice\PhpSpreadsheet\Style\Borders;

use think\facade\Db;
use app\model\HeaderModel;

use think\response\Json;

define('HEADROWNUM', '2'); //定义表头的行号
define('HOST', 'https://cube123.cn');
/* 定义表头位置常量 */
// define('HEADER_COL_MANGBANSHU','Z');

//先自定义一波常量默认值
// define('HEADER_COL_BIAOJI', 'A');
// define('HEADER_COL_ID', 'B');
// define('HEADER_COL_BIANHAO', 'C'); //暂时未使用
// define('HEADER_COL_GONGSI', 'D'); //暂时未使用
// define('HEADER_COL_MINGHUO', 'E');
// define('HEADER_COL_JIBIE', 'F');
// define('HEADER_COL_WORKNAME', 'G');
// define('HEADER_COL_CHEJIAN', 'H');
// define('HEADER_COL_QUESTION', 'I');
// define('HEADER_COL_CONTENT', 'J');
// define('HEADER_COL_JIEZHI', 'K');
// define('HEADER_COL_DEPT', 'L');
// define('HEADER_COL_POS', 'M');
// define('HEADER_COL_STARTTIME', 'N');
// define('HEADER_COL_ENDTIME', 'O');
// define('HEADER_COL_FUZEREN', 'P');
// define('HEADER_COL_PERSON', 'Q');
// define('HEADER_COL_LUXIANG', 'R');
// define('HEADER_COL_GUDINGSHEXIANGTOU', 'S');
// define('HEADER_COL_REPORT', 'T');
// define('HEADER_COL_REPORTSTATE', 'U');
// define('HEADER_COL_REGTIME', 'V');
// define('HEADER_COL_REGPERSON', 'W');

//然后动态重置常量值
const HEADER_MAP = ["审核标记"=>"HEADER_COL_BIAOJI", "序号"=>"HEADER_COL_ID", "车间报备编号"=>"HEADER_COL_BIANHAO", "所属公司"=>"HEADER_COL_GONGSI", "明火或非明火作业"=>"HEADER_COL_MINGHUO", "作业级别"=>"HEADER_COL_JIBIE", "作业项目名称"=>"HEADER_COL_WORKNAME", "所属车间或装置"=>"HEADER_COL_CHEJIAN", "存在的问题"=>"HEADER_COL_QUESTION", "计划检修的内容"=>"HEADER_COL_CONTENT", "原介质"=>"HEADER_COL_JIEZHI", "作业单位"=>"HEADER_COL_DEPT", "作业点具体位置"=>"HEADER_COL_POS", "作业开始时间"=>"HEADER_COL_STARTTIME", "作业结束时间"=>"HEADER_COL_ENDTIME", "属地单位作业负责人"=>"HEADER_COL_FUZEREN", "作业人员"=>"HEADER_COL_PERSON", "是否使用移动监控视频设备"=>"HEADER_COL_LUXIANG", "固定摄像头名称"=>"HEADER_COL_GUDINGSHEXIANGTOU", "是否需报备审批"=>"HEADER_COL_REPORT", "报备状态"=>"HEADER_COL_REPORTSTATE", "登记时间"=>"HEADER_COL_REGTIME", "登记人"=>"HEADER_COL_REGPERSON"];

// 19667 孙晓东

function write1($file = 'C:\\Users\\Cube\\AppData\\Local\\Temp\\123.xlsx'){
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'Hello World !');

    $writer = new Writer\Xlsx($spreadsheet);
    $writer->save($file);
    return $file;
}

// 遍历查询结果并定义常量
// foreach ($constants as $constant) {
//     $name = $constant['name'];
//     $value = $constant['value'];
//     define($name, $value);
// }

// 测试常量是否定义成功
// if (defined('SOME_CONSTANT')) {
//     echo "常量 SOME_CONSTANT 的值是: " . SOME_CONSTANT;
// } else {
//     echo "常量 SOME_CONSTANT 未定义。";
// }

// 函数：根据常量值获取常量名称
function get_constant_name($value, $prefix = '') {
    // 获取所有已定义的常量
    $constants = get_defined_constants(true);

    // 遍历用户定义的常量
    foreach ($constants['user'] as $name => $val) {
        // 如果指定了前缀，只匹配带有该前缀的常量
        if ($prefix && strpos($name, $prefix) !== 0) {
            continue;
        }
        // 找到匹配的值
        if ($val === $value) {
            return $name;
        }
    }
    return null; // 未找到匹配的常量
}


class ExcelTest extends BaseController
{
    private $rootPath = 'uploads/';

    public function read1($file = 'G:\\Users\\Administrator\\Desktop\\123\\苯乙烯8.7报备作业.xlsx')
    {
        $file = realpath($file);
        if(empty($file)){
            // $host = Request()->header('host');
            // if(!strpos($host, "27.0.0.1")){
            //     return '请勿直接访问!';
            // }
            return '请勿直接访问!';
        }
        try {
            $reader = new Reader\Xls();
            $spreadsheet = $reader->load($file);
        } catch (\Exception $e) { //tp6中捕获异常要用\Exception
            if(strpos($e->getMessage(),'OLE'))
            {
                $reader = new Reader\Xlsx();
                $spreadsheet = $reader->load($file);
            }else{
                return [$e->getMessage()];
            }
        }
        $reader->setReadDataOnly(TRUE);
        if($spreadsheet == null)
        {
            return ['code'=> 7777, 'msg'=>"未读取成功"];
        }
        $worksheet = $spreadsheet->getActiveSheet();


        $lines = $worksheet->toArray();
        $table_header = $lines[HEADROWNUM-1];

            for ($col = 0; $col < 26; $col++) {
                $c = chr(65+$col);
                try{
                    $header_name = $table_header[$col];
                    $header_const_col = HEADER_MAP[$header_name];
                    if(!defined($header_const_col)){
                        define($header_const_col, $c);
                    }else{
                        continue;
                    }
                } catch (\Exception $e) {
                    // echo $header_name;
                    // dump($e);
                    // return $e->getMessage();
                }
            }
        // $lines = $worksheet->toArray();
        // $nums = count($lines);
        // echo '<table>' . PHP_EOL;

        // foreach($worksheet->getRowIterator() as $row) {
        //     echo '<tr>' . PHP_EOL;
        // // echo 123;
        //     $cellIterator = $row->getCellIterator();
        //     $cellIterator->setIterateOnlyExistingCells(FALSE);

        //     foreach($cellIterator as $cell){
        //         echo '<td>' .
        //              $cell->getValue() . $cell->getCoordinate() .
        //              '</td>' . PHP_EOL;
        //     }
        //     echo '</tr>' . PHP_EOL;
        // }
        // echo '</table>' . PHP_EOL;

        $highestRow = $worksheet->getHighestDataRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestDataColumn(); // e.g 'F'
        $levels = [];
        // Increment the highest column letter
        ++$highestColumn;

        $tabledata = [];
        $tabledata_linshi = [];

        // 读取文件头
        // $worksheet->getCell()

        // 通过常量前缀,遍历所有表头常量,并将读取的表数据赋值给动态变量
        $constants = get_defined_constants(true)['user'];
        $prefix = 'HEADER_COL_';
        for ($row = HEADROWNUM+1; $row <= $highestRow; ++$row)
        {

            // for ($col = 'A'; $col != $highestColumn; ++$col) {}
            foreach ($constants as $name => $val) {
                if($prefix && strpos($name, $prefix) !==0) {

                } else {
                    $tabledata_linshi[strtolower(substr($name, strrpos($name, '_')+1))] = (string)($worksheet->getCell($val . $row)->getValue());
                }
            }

            $levels[] = $tabledata_linshi['jibie'];
            $tabledata[] = $tabledata_linshi;

        }

        $levels = implode('',$levels); //组合数组为字符串类似join

        $jibiedict = ['特级动火'=> 0,'一级动火'=> 0,'二级动火'=> 0,'受限'=> 0,'Ⅰ级高处'=> 0,'Ⅱ级高处'=> 0,'Ⅲ级高处'=> 0,'Ⅳ级高处'=> 0,'一级吊装'=> 0,'二级吊装'=> 0,'三级吊装'=> 0,'盲板'=> 0,'临时用电'=> 0,'动土'=> 0,'断路'=> 0,'检维修'=> 0,'动火'=> 0,'高处'=> 0,'吊装'=> 0];

        $convert = function($k, $levels) {
            $regex = '/' . $k . '/';
            return preg_match_all($regex, $levels);
        };

        foreach ($jibiedict as $key => $value)
        {
            $jibiedict[$key] = $convert($key, $levels);
        }

        // $timestamp_start_t = strtotime($start_t);

        // $word_url = HOST . "/tp6/public/index.php/word/exportWord?workdata[]=$jibiedict[特级动火]&workdata[]=$jibiedict[一级动火]&workdata[]=$jibiedict[二级动火]&workdata[]=$jibiedict[受限]&workdata[]=$jibiedict[盲板]&workdata[]=$jibiedict[高处]&workdata[]=$jibiedict[吊装]&workdata[]=$jibiedict[临时用电]&workdata[]=$jibiedict[动土]&workdata[]=$jibiedict[断路]&workdata[]=$jibiedict[检维修]&riqi=$timestamp_start_t";

        // // Get the current date/time and convert to an Excel date/time
        // $dateTimeNow = time();
        // $excelDateValue = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel( $dateTimeNow );
        $word_url = '';
        $start_t = '';
        if(!Request()->isPost()){
            return json(['code'=>0, 'msg'=> '成功', 'count'=> count($tabledata), 'data'=> $tabledata, 'url'=>$word_url, 'time'=>$start_t]);
        }else{
            return ['code'=>0, 'msg'=> '成功', 'count'=> count($tabledata), 'data'=> $tabledata, 'url'=>$word_url, 'time'=>$start_t];
        }
    }


    // 127.0.0.1:8010/tp6/public/ExcelTest/index
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            return View::fetch();
        }
        elseif ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $file = $request->file('file');
            $ret = ['code'=>-1, 'msg'=>'未知错误', 'data'=>[] ];

            try {
                $targetPath = $this->movefile($file, '报备台账临时目录');
                $targetPath = $targetPath->getPathname();

                if(file_exists($targetPath)) {
                    $ret['code'] = 0;
                    $ret['msg'] = '文件上传成功！' . $targetPath;
                } else {
                    $ret['code'] = 5555;
                    $ret['msg'] = '文件上传失败！';
                }
                $result = $this->read1($targetPath);
            } catch (\Exception $e) {
                $ret['code'] = 9999;
                $ret['msg'] = $e->getMessage();
            }

            $tmpdata = $result['data'];

            $result['data'] = $tmpdata;
            $ret = array_merge($ret, $result);
            return $ret;
        }
    }


    // https://blog.csdn.net/json_ligege/article/details/128854823
    /**
     * 生成一个随机名字(当前的年月日时分秒+随机数字+后缀名)
     * @return string 文件的新名字
     */
    protected function randName($withDate = false)
    {
        // 1, 生成文件的时间部分
        $name = date('YmdHis');
        if(!$withDate)$name = '';

        // 2, 加上随机产生的6位数
        $str = '0987653214';
        for ($i = 0; $i < 6; $i++) {
            $name .= $str[mt_rand(0, strlen($str) - 1)];
        }
        return $name;
    }


    private $allowed_ext = ['png', 'jpg', 'jpeg', 'pdf', 'webp', 'xls', 'xlsx', 'txt', 'bmp'];

    /**
     * 实现文件上传
     * @param object $file 上传的文件的对象信息
     * @param string $path 文件上传的目录
     * @return string|array 上传失败返回原因 成功返回文件的新名字数组
     */
    private function movefile($file, $path)
    {
        // return $file->getRealPath();//获取文件的临时路径
        // return $file->getOriginalName(); //文件名称
        // return $file->getOriginalMime(); //文件类型
        // return $file->getPathname(); //文件上传路径
        // return $file->getOriginalExtension(); //文件后缀
        // return $file->move($filePath, $newname); //上传文件
        try {
            // 判断逻辑错误
            $maxSize = 80 * 1024;
            if ($file->getSize() > $maxSize) {
                return '上传失败，超出了文件限制的大小！';
            }

            // 判断文件类型
            $ext = strtolower($file->getOriginalExtension());
            // if (!in_array($ext, $this->allowed_ext, true)) {
            //     // 非法的文件类型
            //     return '上传的图片的类型不正确，允许的类型有：' . implode(',', $this->allowed_ext);
            // }

            // 文件原名称(带扩展类型)
            $originalName = $file->getOriginalName();
            $originalName = /*'-' . $this->randName() . */'.' . $ext;

            // 得到文件随机名称和相应文件夹
            $newname = date('Ymd') . '-' . time() . $originalName;
            $filePath = $this->rootPath . $path . '/';
            // $filePath = $this->rootPath . $path . '/' . date('Ymd') . '/';
            $savename = $file->move($filePath, $newname);
            return $savename;
            return ['path' => $savename];
        } catch (\Exception $e) {
            return $e->getMessage() . $e->getLine();
        }
    }

    public function tuozhuai(Request $request)
    {
        if($request->isPost()){
            return 123;
        }
    }
}
