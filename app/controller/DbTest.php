<?php
// http://localhost:8010/tp6/public/DbTest
namespace app\controller;

use think\facade\Db;

class DbTest
{
    public function index()
    {
        $data = Db::table('tb1')->select();
        dump($data);
		// return json($data);
    }
}


