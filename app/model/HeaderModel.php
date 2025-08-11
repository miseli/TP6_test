<?php
namespace app\model;

use think\Model;

class HeaderModel extends Model
{
	// protected $name = 'user';
	// protected $name = 'baobei_header';
	protected $table = "baobei_header";
	public function queryall()
	{
		// $data = HeaderModel::order('id')->limit(10)->select();
		$data = HeaderModel::select();
		// return $data;
		return $data->toArray();
	}

	protected static function init()
	{
		// TODO:初始化内容
	}
}