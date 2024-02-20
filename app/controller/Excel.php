<?php
declare (strict_types = 1);

namespace app\controller;

use think\Request;
use think\Response;
use app\BaseController;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer;
use PhpOffice\PhpSpreadsheet\Reader;
use PhpOffice\PhpSpreadsheet\Style\Borders;

class Excel extends BaseController
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
		$without = _GET('n');
		$filename = _GET('outname');

        $dict = ['','B12','B13','D3','D4','D5','D6','D7','D8','D9','D10','D11','D12'];
        $templatefile = realpath('.') . '/公司风险研判签到表模板.xlsx';
        $reader = new Reader\Xlsx();
        $spreadsheet = $reader->load($templatefile);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', '乙烯分公司                     会议时间： '.$riqi);
        // // 生成数据
        // $sheet->fromArray($data, null, "A2");
        // // 样式设置
        // $sheet->getDefaultColumnDimension()->setWidth(12);
        if(!is_null($without)){
            foreach ($without as $item) {
                // 填写内容
                $sheet->setCellValue($dict[$item], '无作业');
                // 打斜杠
                // $sheet->getStyle($dict[$item])->getBorders()->setDiagonalDirection(Borders::DIAGONAL_UP);
                // $sheet->getStyle($dict[$item])->getBorders()->getDiagonal()->setBorderStyle('thin');
            }
        }

        \app\third_lib\ImportExportSetting::export_set($filename, 'xlsx');
        // 调用方法执行下载
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        // 数据流
        $writer->save("php://output");
    }


	private function hebingforeground($backgroundImage, $foregroundImage, $x, $y)
	{
		// 加载前景图像（PNG）
		$foregroundImage = imagecreatefrompng($foregroundImage);

		// 获取前景图像的宽度和高度
		$foregroundWidth = imagesx($foregroundImage);
		$foregroundHeight = imagesy($foregroundImage);
		
		// 将前景图像复制到背景图像的指定位置
		// 这里假设你想将前景图像放置在背景图像的左上角（0, 0）位置
		if($foregroundWidth<=300){
			imagecopy($backgroundImage, $foregroundImage, random_int(430, 480) + 60 + $x, 480+$y, 0, 0, $foregroundWidth, $foregroundHeight);
		}else{
			imagecopy($backgroundImage, $foregroundImage, random_int(430, 490) + $x, 480+$y, 0, 0, $foregroundWidth, $foregroundHeight);
		}
		// imagecopy($backgroundImage, $foregroundImage, $x, 480+$y, 0, 0, $foregroundWidth, $foregroundHeight);
		imagedestroy($foregroundImage);
	}
	
	private function hebingtext($backgroundImage, $text, $fontSize=30, $textX=181, $textY=295)
	{
		// 设置文字颜色（RGB 格式）
		$textColor = imagecolorallocate($backgroundImage, 0, 0, 0); // 黑色

		// 设置字体文件路径和大小
		$font = realpath('.') . '\\sign\\songti.ttf'; // 使用 TrueType 字体文件
		$fontSize = 30; // 字体大小
		
		// $text = '乙烯分公司                       会议时间： ' . $riqi; // 要添加的文字
		// $textX = 181; // 文字开始的 X 坐标
		// $textY = 295; // 文字开始的 Y 坐标
		
		// 添加文字到图片上
		imagettftext($backgroundImage, $fontSize, 0, $textX, $textY, $textColor, $font, $text);
	}

	public function getimage($outname = 'image', $n=[], $ts)
	{
        $riqi = date("Y 年 m 月 d 日", intval($ts));
				
		// 加载背景图像（JPG）
		$backgroundImage = imagecreatefromjpeg(realpath('.') . '/sign/background.jpg');
		
		$this->hebingforeground($backgroundImage, realpath('.') . '/sign/manager/1.png', 0, 0);

		//处理经理与处室
		$fruits = [2,3,4,5,6];
		shuffle($fruits);
		for($i=0;$i<=4;$i++){
			$this->hebingforeground($backgroundImage, realpath('.') . '/sign/manager/' . $fruits[$i] . '.png', 0, ($i+1)*160);
		}
		$this->hebingforeground($backgroundImage, realpath('.') . '/sign/manager/7.png', 0, 6*160);
		$this->hebingforeground($backgroundImage, realpath('.') . '/sign/manager/8.png', 0, 7*160);
		$this->hebingforeground($backgroundImage, realpath('.') . '/sign/manager/9.png', 0, 8*160);
		
		if(!is_null($n)){
			//处理有作业车间
			$fruits = array_diff([1,2,3,4,5,6,7,8,9,10,11], $n);
			foreach ($fruits as $i)
			{
				if($i==1 || $i==2){//乙烯聚乙烯
					$this->hebingforeground($backgroundImage, realpath('.') . '/sign/' . $i . '.png', 0, ($i+8)*160);
				}else{
					$this->hebingforeground($backgroundImage, realpath('.') . '/sign/' . $i . '.png', 670, ($i-3)*160);
				}
			}
			//处理无作业车间
			foreach($n as $i)
			{
				if($i==1||$i==2){//乙烯聚乙烯
					$this->hebingtext($backgroundImage, '无作业', 26, 560, 550+($i+8)*160);
				}else{
					$this->hebingtext($backgroundImage, '无作业', 26, 1230, 550+($i-3)*160);				
				}
			}
		}
		
		$this->hebingtext($backgroundImage, '乙烯分公司                       会议时间： ' . $riqi);
		
		// 输出合并后的图像到浏览器或保存到文件
		// \app\third_lib\ImportExportSetting::view_set($outname, 'jpg');
		\app\third_lib\ImportExportSetting::export_set('CCI' . $outname, 'jpg');
		imagejpeg($backgroundImage);

		// 释放内存
		imagedestroy($backgroundImage);
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
