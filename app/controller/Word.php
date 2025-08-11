<?php
declare (strict_types = 1);

namespace app\controller;

use think\Request;
use PhpOffice\PHPWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;

class Word
{

    function exportWord(Request $request, $file_dir='风险研判签到缓存/'){
        $workdata = _GET('workdata');
        $riqi = intval(_GET('riqi'));
        $cbs = $request->param('cbs');
        $ssc = $request->param('ssc');
        $ktc = $request->param('ktc');

        if(count($request->param())<=1){
            $workdata = [0,0,0,0,0, 0,0,0,0,0, 0];
            $cbs = '否';
            $ssc = '否';
            $ktc = '否';
        }

        if($riqi==0){
            return <<<HERE
            请填入日期参数,例如:2025.07.22<br/>
            <a href="http://localhost:8010/tp6/public/index.php/word/exportWord?riqi=1753142400">http://localhost:8010/tp6/public/index.php/word/exportWord?riqi=1753142400</a><br/>
            明天: <a id="a1" href=""></a> 后天: <a id="a2" href=""></a><br/>
            <a id="a3" href=""></a><br/>
            <a id="a4" href=""></a><br/>
            <a id="a5" href=""></a><br/>
            <a id="a6" href=""></a><br/>
            <a id="a7" href=""></a><br/>
            <a id="a8" href=""></a><br/>
            <a id="a9" href=""></a><br/>
            <script>
                for(let i=1; i<10; i++){
                    let a1 = document.getElementById('a' + i);
                    a1.href = 'http://localhost:8010/tp6/public/index.php/word/exportWord?riqi=' + (new Date().getTime()+24 * 60 * 60 * 1000 * i)/1000
                    a1.innerText = new Date(new Date().getTime() + 24 * 60 * 60 * 1000 * i).toLocaleDateString()
                }

            </script>
HERE;
        }

        $filename = '乙烯分公司风险研判'.date('m.d',$riqi);
        $riqi = date("Y 年 m 月 d 日", $riqi);

        $count = $workdata[0]+$workdata[1]+$workdata[2]+$workdata[3]+$workdata[4]+$workdata[5]+$workdata[6]+$workdata[7]+$workdata[8]+$workdata[9]+$workdata[10];

        // if ($count==0) {
        //     $template = new TemplateProcessor(realpath('.') . '/乙烯分公司风险研判模板0.docx');
        // }elseif ($count>0) {
        //     $template = new TemplateProcessor(realpath('.') . '/乙烯分公司风险研判模板.docx');
        // }

        $template = new TemplateProcessor(realpath('.') . '/乙烯分公司风险研判模板.docx');

        // phpword 1.3.0版本有bug。详见https://github.com/PHPOffice/PHPWord/pull/2748
        // phpword 1.4.0中对此bug做出修复,修复方法如下:
        // PHPWord/src/PhpWord/TemplateProcessor.php文件中
        // return $subject ? Text::toUTF8($subject) : ''; >>> return (null !== $subject) ? Text::toUTF8($subject) : '';
        $template->setValue('riqi', $riqi);
        $template->setValue('teji', $workdata[0]);
        $template->setValue('yiji', $workdata[1]);
        $template->setValue('erji', $workdata[2]);
        $template->setValue('shx', $workdata[3]);
        $template->setValue('mb', $workdata[4]);
        $template->setValue('gch', $workdata[5]);
        $template->setValue('dzh', $workdata[6]);
        $template->setValue('ld', $workdata[7]);
        $template->setValue('dt', $workdata[8]);
        $template->setValue('dl', $workdata[9]);
        $template->setValue('wu', $workdata[10]);
        $template->setValue('ktc', $ktc); //开停车
        $template->setValue('cbs', $cbs); //承包商
        $template->setValue('ssc', $ssc); //试生产

        \app\third_lib\ImportExportSetting::export_set($filename, 'docx');
        // 数据流
        $template->saveAs("php://output");
    }

    /**
     * 构造函数
     *
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return 'Word控制器';
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
