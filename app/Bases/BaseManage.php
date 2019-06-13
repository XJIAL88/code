<?php

namespace App\Bases;

/**
 * 数据库 基类
 * User: Administrator
 * Date: 2016/7/12
 * Time: 19:23
 */
class BaseManage
{
	/**
	 * 排序ASC
	 * @param int $nextId
	 * @param int $pageSize
	 * @param bool $add
	 * @return string
	 */
	static function dbOrderSqlByAsc($nextId = 0, $pageSize = 0, $add = true)
	{
		if ($pageSize === 0) {
			return ($add ? ' AND' : '') . ' 1=1 ORDER BY id ASC';
		}
		if ($nextId) {
			return ($add ? ' AND' : '') . ' id>' . intval($nextId) . ' ORDER BY id ASC LIMIT ' . intval($pageSize);
		} else {
			return ($add ? ' AND' : '') . ' 1=1 ORDER BY id ASC LIMIT ' . intval($pageSize);
		}
	}

	/**
	 * 排序DESC
	 * @param int $nextId
	 * @param int $pageSize
	 * @param bool $add
	 * @return string
	 */
	static function dbOrderSqlByDesc($nextId = 0, $pageSize = 0, $add = true)
	{
		if ($pageSize === 0) {
			return ($add ? ' AND' : '') . ' 1=1 ORDER BY id DESC';
		}
		if ($nextId) {
			return ($add ? ' AND' : '') . ' id<' . intval($nextId) . ' ORDER BY id DESC LIMIT ' . intval($pageSize);
		} else {
			return ($add ? ' AND' : '') . ' 1=1 ORDER BY id DESC LIMIT ' . intval($pageSize);
		}
	}
}