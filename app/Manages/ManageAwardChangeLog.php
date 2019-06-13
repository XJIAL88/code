<?php

namespace App\Manages;

use App\Models\ModelAwardChangeLog;
use Illuminate\Support\Facades\DB;

/**
 * 奖品配置变更
 * User: Administrator
 * Date: 2016/7/12
 * Time: 19:23
 */
class ManageAwardChangeLog extends ModelAwardChangeLog
{
	/**
	 * 批量添加数据
	 * @param string $connection
	 * @param array $params
	 * @return mixed
	 */
	static function insertAll($connection = '', $params = array())
	{
		return  DB::connection($connection)->table(self::$table)->insert($params);
	}

}