<?php
namespace app\controller;

use app\BaseController;
use WpOrg\Requests\Session;
use WpOrg\Requests\Requests;
use WpOrg\Requests\Cookie;
use WpOrg\Requests\Hooks;

use app\model\Order as Tiku;
use think\Facade\Db;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer;
use PhpOffice\PhpSpreadsheet\Reader;
use PhpOffice\PhpSpreadsheet\Style\Borders;

// use WpOrg\Requests\Autoload;
// require_once '../../vendor/autoload.php';

class Cube extends BaseController
{
    protected $middleware = ['check'];
	// http://localhost:8020/index.php/cube/index
	// http://localhost:8020/index.php?s=cube/hello/index
    public function index()
    {
        // var_dump(Db::query('select * from newtest'));
        // var_dump(Db::query('select * from newtest where id=:id', ['id'=>578330]));

        $dataset = Tiku::name('newtest')->where([
            ['id', '=', 578299],
            ['jobid', 'exp', Db::raw('=1947')]
        ])->selectOrFail()->toArray();
        return json_encode($dataset[0]);
        // $d = new Tiku();
        // $d->save(['id'=>2017, 'answer'=>132456, 'options'=>45789, 'title'=>'题干']);
        return '<div style="font-size: 100px">Cube</div>';
    }
	// http://localhost:8020/index.php?s=cube/hello/name/123
	// http://localhost:8020/index.php/cube/hello/name/123
    public function hello($name = 'ThinkPHP6')
    {
        $url = 'http://127.0.0.1:8010';
        $session = new Session($url);
        $res = $session->get('/cube');
        return $res->body;
        // return 'Cube::hello(name) print ' . $name;
        // $headers = ['content-type'=> 'application/json'];
        // $data = ['some'=>'data'];
        // Requests::post(url, $headers, json_encode($data));
        // $res = Requests::get('https://cube123.cn');
        // Autoload::register();
        // return $res->status_code;
        // return $res->body;
        // var_dump($res->body);
        // return isset($res->headers['date']);
    }

    public function test(){
        return 'test';
    }
	
	private function read($file){
		$reader = new Reader\Xls();
		$spreadsheet = $reader->load($file);
		$lines = $spreadsheet->getActiveSheet()->toArray();
		return $lines;
	}
	
    public function excel(){
		$lines = $this->read('G:/www/作业报备台账2022-11-17.xls');
        // return json($lines);
        return json(file('G:\\www\\tp6\\app\\controller\\Login.php'));
    }
}