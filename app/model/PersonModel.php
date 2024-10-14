<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;
use think\facade\Db;

/**
 * @mixin \think\Model
 */
class PersonModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'tb1';

    // 自动写入时间戳字段，默认情况下，会自动生成create_time和update_time字段
    protected $autoWriteTimestamp = 'datetime';

    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    // 允许写入的数据字段
    protected $insert = ['name', 'picture', 'tel', 'dept', 'exported'];

    // 允许更新的数据字段
    protected $update = ['name', 'picture', 'tel', 'dept', 'exported'];
}
