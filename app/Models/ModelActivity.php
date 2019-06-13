<?php

namespace App\Models;

use App\Bases\BaseModel;

/**
 * 权限表
 * User: Administrator
 * Date: 2016/7/12
 * Time: 19:23
 */
class ModelActivity extends BaseModel
{
	/**
	 * 表名
	 * @var string
	 */
	static $table = 'db_activity';

	/**
	 * 获取单条数据
	 * @param string $connection
	 * @param $id
	 * @param bool $lockForUpdate
	 * @return mixed
	 */
	static function get($connection = '', $id, $lockForUpdate = false)
	{
		return self::dbGet($connection, self::$table, $id, $lockForUpdate);
	}

	/**
	 * 获取单条数据（根据查询条件）
	 * @param string $connection
	 * @param $field
	 * @param $param
	 * @param bool $lockForUpdate
	 * @return mixed
	 */
	static function getWith($connection = '', $field, $param, $lockForUpdate = false)
	{
		return self::dbGetWith($connection, self::$table, $field, $param, $lockForUpdate);
	}

	/**
	 * 获取单条数据（根据多项查询条件）
	 * @param string $connection
	 * @param array $wheres
	 * @param array $orders
	 * @param bool $lockForUpdate
	 * @return mixed
	 */
	static function getWithWheres($connection = '', $wheres = array(), $orders = array(), $lockForUpdate = false)
	{
		return self::dbGetWithWheres($connection, self::$table, $wheres, $orders, $lockForUpdate);
	}

	/**
	 * 获取列表
	 * @param string $connection
	 * @param array $wheres
	 * @param array $orders
	 * @param int $pageSize
	 * @param int $pageNumber
	 * @param bool $lockForUpdate
	 * @return mixed
	 */
	static function getList($connection = '', $wheres = array(), $orders = array(), $pageSize = 0, $pageNumber = 1, $lockForUpdate = false)
	{
		return self::dbGetList($connection, self::$table, $wheres, $orders, $pageSize, $pageNumber, $lockForUpdate);
	}

	/**
	 * 获取数量
	 * @param string $connection
	 * @param array $wheres
	 * @param bool $lockForUpdate
	 * @return mixed
	 */
	static function getCount($connection = '', $wheres = array(), $lockForUpdate = false)
	{
		return self::dbGetCount($connection, self::$table, $wheres, $lockForUpdate);
	}

	/**
	 * 添加
	 * @param string $connection
	 * @param array $params
	 * @return mixed
	 */
	static function insert($connection = '', $params = array())
	{
		return self::dbInsert($connection, self::$table, $params);
	}

	/**
	 * 编辑
	 * @param string $connection
	 * @param $id
	 * @param array $params
	 * @return mixed
	 */
	static function update($connection = '', $id, $params = array())
	{
		return self::dbUpdate($connection, self::$table, $id, $params);
	}

	/**
	 * 编辑（根据多项条件）
	 * @param string $connection
	 * @param array $wheres
	 * @param array $params
	 * @return mixed
	 *
	 */
	static function updateWithWheres($connection = '', $wheres = array(), $params = array())
	{
		return self::dbUpdateWithWheres($connection, self::$table, $wheres, $params);
	}

	/**
	 * 编辑（自增）
	 * @param string $connection
	 * @param $id
	 * @param $field
	 * @param $param
	 * @return mixed
	 */
	static function increment($connection = '', $id, $field, $param)
	{
		return self::dbIncrement($connection, self::$table, $id, $field, $param);
	}

	/**
	 * 编辑（根据多项条件自增）
	 * @param string $connection
	 * @param $field
	 * @param $param
	 * @param array $wheres
	 * @param array $params
	 * @return mixed
	 */
	static function incrementWithWheres($connection = '', $field, $param, $wheres = array(), $params = array())
	{
		return self::dbIncrementWithWheres($connection, self::$table, $field, $param, $wheres, $params);
	}

	/**
	 * 编辑（自减）
	 * @param string $connection
	 * @param $id
	 * @param $field
	 * @param $param
	 * @return mixed
	 */
	static function decrement($connection = '', $id, $field, $param)
	{
		return self::dbDecrement($connection, self::$table, $id, $field, $param);
	}

	/**
	 * 编辑（根据多项条件自减）
	 * @param string $connection
	 * @param $field
	 * @param $param
	 * @param array $wheres
	 * @param array $params
	 * @return mixed
	 */
	static function decrementWithWheres($connection = '', $field, $param, $wheres = array(), $params = array())
	{
		return self::dbDecrementWithWheres($connection, self::$table, $field, $param, $wheres, $params);
	}

	/**
	 * 删除
	 * @param string $connection
	 * @param $id
	 * @return mixed
	 */
	static function delete($connection = '', $id)
	{
		return self::dbDelete($connection, self::$table, $id);
	}

	/**
	 * 删除（根据多项条件）
	 * @param string $connection
	 * @param array $wheres
	 * @return mixed
	 */
	static function deleteWithWheres($connection = '', $wheres = array())
	{
		return self::dbDeleteWithWheres($connection, self::$table, $wheres);
	}
}