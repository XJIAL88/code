<?php

namespace App\Bases;

use Illuminate\Support\Facades\DB;

/**
 * 数据库 基类
 * User: Administrator
 * Date: 2016/7/12
 * Time: 19:23
 */
class BaseModel
{
	/**
	 * 获取单条数据
	 * @param string $connection
	 * @param $table
	 * @param $id
	 * @param bool $lockForUpdate
	 * @return mixed
	 */
	static function dbGet($connection = '', $table, $id, $lockForUpdate = false)
	{
		if ($lockForUpdate) {
			return DB::connection($connection)->table($table)->where('id', intval($id))->lockForUpdate()->first();
		} else {
			return DB::connection($connection)->table($table)->where('id', intval($id))->first();
		}
	}

	/**
	 * 获取单条数据（根据查询条件）
	 * @param string $connection
	 * @param $table
	 * @param $field
	 * @param $param
	 * @param bool $lockForUpdate
	 * @return mixed
	 */
	static function dbGetWith($connection = '', $table, $field, $param, $lockForUpdate = false)
	{
		if ($lockForUpdate) {
			return DB::connection($connection)->table($table)->where($field, $param)->lockForUpdate()->first();
		} else {
			return DB::connection($connection)->table($table)->where($field, $param)->first();
		}
	}

	/**
	 * 获取单条数据（根据多项查询条件）
	 * @param string $connection
	 * @param $table
	 * @param array $wheres
	 * @param array $orders
	 * @param bool $lockForUpdate
	 * @return mixed
	 */
	static function dbGetWithWheres($connection = '', $table, $wheres = array(), $orders = array(), $lockForUpdate = false)
	{
		$model = DB::connection($connection)->table($table);
		$model = self::__wheres($model, $wheres);
		$model = self::__orders($model, $orders);
		if ($lockForUpdate) {
			return $model->lockForUpdate()->first();
		} else {
			return $model->first();
		}
	}

	/**
	 * 获取列表
	 * @param string $connection
	 * @param $table
	 * @param array $wheres
	 * @param array $orders
	 * @param int $pageSize
	 * @param int $pageNumber
	 * @param bool $lockForUpdate
	 * @return mixed
	 */
	static function dbGetList($connection = '', $table, $wheres = array(), $orders = array(), $pageSize = 0, $pageNumber = 1, $lockForUpdate = false)
	{
		$model = DB::connection($connection)->table($table);
		$model = self::__wheres($model, $wheres);
		$model = self::__orders($model, $orders);
		if ($pageSize > 0) {
			$model->paginate($pageSize, array('*'), 'pageNumber', $pageNumber);
		}
		if ($lockForUpdate) {
			return $model->lockForUpdate()->get();
		} else {
			return $model->get();
		}
	}

	/**
	 * 获取数量
	 * @param string $connection
	 * @param $table
	 * @param array $wheres
	 * @param bool $lockForUpdate
	 * @return mixed
	 */
	static function dbGetCount($connection = '', $table, $wheres = array(), $lockForUpdate = false)
	{
		$model = DB::connection($connection)->table($table);
		$model = self::__wheres($model, $wheres);
		if ($lockForUpdate) {
			return $model->lockForUpdate()->count();
		} else {
			return $model->count();
		}
	}

	/**
	 * 添加
	 * @param string $connection
	 * @param $table
	 * @param array $params
	 * @return mixed
	 */
	static function dbInsert($connection = '', $table, $params = array())
	{
		$params['create_at'] = time();
		return DB::connection($connection)->table($table)->insertGetId($params);
	}

	/**
	 * 编辑
	 * @param string $connection
	 * @param $table
	 * @param $id
	 * @param array $params
	 * @return mixed
	 */
	static function dbUpdate($connection = '', $table, $id, $params = array())
	{
		$params['update_at'] = time();
		return DB::connection($connection)->table($table)->where('id', $id)->update($params);
	}

	/**
	 * 编辑（根据多项条件）
	 * @param string $connection
	 * @param $table
	 * @param array $wheres
	 * @param array $params
	 * @return mixed
	 */
	static function dbUpdateWithWheres($connection = '', $table, $wheres = array(), $params = array())
	{
		$params['update_at'] = time();
		$model = DB::connection($connection)->table($table);
		$model = self::__wheres($model, $wheres);
		return $model->update($params);
	}

	/**
	 * 编辑（自增）
	 * @param string $connection
	 * @param $table
	 * @param $id
	 * @param $field
	 * @param $param
	 * @return mixed
	 */
	static function dbIncrement($connection = '', $table, $id, $field, $param)
	{
		$params['update_at'] = time();
		return DB::connection($connection)->table($table)->where('id', $id)->increment($field, $param);
	}

	/**
	 * 编辑（根据多项条件自增）
	 * @param string $connection
	 * @param $table
	 * @param $field
	 * @param $param
	 * @param array $wheres
	 * @param array $params
	 * @return mixed
	 */
	static function dbIncrementWithWheres($connection = '', $table, $field, $param, $wheres = array(), $params = array())
	{
		$params['update_at'] = time();
		$model = DB::connection($connection)->table($table);
		$model = self::__wheres($model, $wheres);
		return $model->increment($field, $param, $params);
	}

	/**
	 * 编辑（自减）
	 * @param string $connection
	 * @param $table
	 * @param $id
	 * @param $field
	 * @param $param
	 * @return mixed
	 */
	static function dbDecrement($connection = '', $table, $id, $field, $param)
	{
		$params['update_at'] = time();
		return DB::connection($connection)->table($table)->where('id', $id)->decrement($field, $param, $params);
	}

	/**
	 * 编辑（根据多项条件自减）
	 * @param string $connection
	 * @param $table
	 * @param $field
	 * @param $param
	 * @param array $wheres
	 * @param array $params
	 * @return mixed
	 */
	static function dbDecrementWithWheres($connection = '', $table, $field, $param, $wheres = array(), $params = array())
	{
		$params['update_at'] = time();
		$model = DB::connection($connection)->table($table);
		$model = self::__wheres($model, $wheres);
		return $model->decrement($field, $param, $params);
	}

	/**
	 * 删除
	 * @param string $connection
	 * @param $table
	 * @param $id
	 * @return mixed
	 */
	static function dbDelete($connection = '', $table, $id)
	{
		return DB::connection($connection)->table($table)->where('id', $id)->delete();
	}

	/**
	 * 删除（根据多项条件）
	 * @param string $connection
	 * @param $table
	 * @param array $wheres
	 * @return mixed
	 */
	static function dbDeleteWithWheres($connection = '', $table, $wheres = array())
	{
		$model = DB::connection($connection)->table($table);
		$model = self::__wheres($model, $wheres);
		return $model->delete();
	}

	/**
	 * 排序处理
	 * @param $model
	 * @param array $orders
	 * @return mixed
	 */
	static function __orders($model, $orders = array())
	{
		if (empty($orders)) {
			$orders = array('id' => 'DESC');
		}
		foreach ($orders as $key => $item) {
			$model->orderBy($key, $item);
		}
		return $model;
	}

	/**
	 * 条件处理
	 * @param $model
	 * @param array $wheres
	 * @return mixed
	 */
	static function __wheres($model, $wheres = array())
	{
		if (empty($wheres)) {
			$wheres = array();
		}
		foreach ($wheres as $key => $item) {
			if (is_array($item) && count($item) === 2) {
				if ($item[0] === 'IN' && is_array($item[1])) {
					// $wheres['number'] = array('IN', array(1, 2));
					$model->whereIn($key, $item[1]);
				} else {
					// $wheres['number'] = array('>', 1);
					$model->where($key, $item[0], $item[1]);
				}
			} else if (is_array($item) && count($item) === 3) {
				if ($item[1] === 'IN' && is_array($item[2])) {
					// $wheres[] = array('number', 'IN', array(1, 2));
					$model->whereIn($item[0], $item[2]);
				} else {
					// $wheres[] = array('number', '>', 1);
					$model->where($item[0], $item[1], $item[2]);
				}
			} else {
				// $wheres['number'] = 1;
				$model->where($key, $item);
			}
		}
		return $model;
	}
}