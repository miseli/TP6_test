<?php
// http://127.0.0.1:8010/tp6/public/ruchang/exportWord
declare (strict_types = 1);

namespace app\controller;

use DateTime;
use think\Request;
use PhpOffice\PHPWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use app\BaseController;

class Ruchang extends BaseController
{

    function exportWord(){
        if ($this->request->isGet()) {

            $type = _GET('type');
            $riqi = intval(_GET('riqi'));
            $filename = '入厂审批单'.date('m.d', $riqi);
            $riqi = date("Y 年 m 月 d 日", $riqi);

            if ($type==0) {
                $filename = '车辆' . $filename;
                $template = new TemplateProcessor(realpath('.') . '/车辆入厂审批单.docx');
            }elseif ($type>0) {
                $filename = '人员' . $filename;
                $template = new TemplateProcessor(realpath('.') . '/人员入厂审批单.docx');
            }

            $array = $this->request->get();

            $startTime = new DateTime($array['startTime']);
            $endTime = new DateTime($array['endTime']);
            $diff = $startTime->diff($endTime);
            $cartime = $diff->d <= 5 ? '车辆临时进场√                 车辆长期进场×':'车辆临时进场×                 车辆长期进场√';

            $template->setValue('riqi', $riqi);
            $template->setValue('cartime', $cartime);

            foreach ($array as $key => $value) {
                $template->setValue($key, $value);
            }
        } else {
            return false;
            die();
        }

        \app\third_lib\ImportExportSetting::export_set($filename, 'docx');
        // 数据流
        $template->saveAs("php://output");
        return true;
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return '入厂审批表控制器';
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
