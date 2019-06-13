<?php

namespace App\Manages;

use App\Models\ModelActivityAward;
use Illuminate\Support\Facades\DB;

/**
 * 活动奖项表
 * User: Administrator
 * Date: 2016/7/12
 * Time: 19:23
 */
class ManageActivityAward extends ModelActivityAward
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