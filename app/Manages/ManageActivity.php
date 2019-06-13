<?php

namespace App\Manages;

use App\Models\ModelActivity;
use Illuminate\Support\Facades\DB;

/**
 * 活动表
 * User: Administrator
 * Date: 2016/7/12
 * Time: 19:23
 */
class ManageActivity extends ModelActivity
{
	/**
	 * 搜索数量
	 * @param $connection
	 * @param $search
	 */
	static function getSearchCount($connection, $search)
	{
		$model = DB::connection($connection)->table(self::$table);
		$model = $model->where('is_award', 1);
		if (!empty($search)){
			$model = $model->where(function ($query) use ($search) {
				$query->where('number', 'like', "%" . $search . "%")
					->orWhere('name', 'like', "%" . $search . "%");
			});
		}
		return $model->count();
	}
	/**
	 * 搜索列表
	 * @param $connection
	 * @param $search
	 */
	static function getSearchGetList($connection,  $search, $orders, $pageSize, $pageNumber)
	{
		$model = DB::connection($connection)->table(self::$table);
		$model = $model->where('is_award', 1);
		if (!empty($search)){
			$model = $model->where(function ($query) use ($search) {
				$query->where('number', 'like', "%" . $search . "%")
					->orWhere('name', 'like', "%" . $search . "%");
			});
		}
		$model = self::__orders($model, $orders);
		if ($pageSize > 0) {
			$model->paginate($pageSize, array('*'), 'pageNumber', $pageNumber);
		}
		return $model->get();
	}
}