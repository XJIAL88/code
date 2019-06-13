<?php

namespace App\Manages;

use App\Bases\BaseManage;
use App\Models\ModelLotteryRecord;
use Illuminate\Support\Facades\DB;

/**
 * 抽奖-抽奖记录
 * User: Administrator
 * Date: 2016/7/12
 * Time: 19:23
 */
class ManageLotteryRecord extends ModelLotteryRecord
{
	/**
	 * 抽奖历史记录数量
	 * @param string $connection
	 * @param $search
	 * @param $status
	 * @param $startAt
	 * @param $endAt
	 * @return mixed
	 */
	static function getCountBySearch($connection, $search, $status, $startAt, $endAt)
	{
		$model = DB::connection($connection)->table(self::$table);
		if (!empty($search)) {
			$model = $model->where(function ($query) use ($search) {
				$query->orwhere('activity_name', 'like', '%' . $search . '%')
					->orwhere('activity_number', 'like', '%' . $search . '%')
					->orwhere('user_mobile', 'like', '%' . $search . '%');
			});
		}

		if ($startAt) {
			$model = $model->where('create_at', '>=', $startAt);
		}
		if ($endAt) {
			$model = $model->where('create_at', '<=', $endAt);
		}

		if (!empty($status)) {
			if (is_array($status)) {
				$model = $model->whereIn('status', $status);
			} else {
				$model = $model->where('status', $status);
			}
		}

		return $model->count();
	}

	/**
	 * 抽奖历史记录列表
	 * @param string $connection
	 * @param $search
	 * @param $status
	 * @param $startAt
	 * @param $endAt
	 * @return mixed
	 */
	static function getListBySearch($connection, $search, $status, $startAt, $endAt, $orders,$pageSize, $pageNumber)
	{
		$model = DB::connection($connection)->table(self::$table);
		if (!empty($search)) {
			$model = $model->where(function ($query) use ($search) {
				$query->orwhere('activity_name', 'like', '%' . $search . '%')
					->orwhere('activity_number', 'like', '%' . $search . '%')
					->orwhere('user_mobile', 'like', '%' . $search . '%');
			});
		}

		if ($startAt) {
			$model = $model->where('create_at', '>=', $startAt);
		}
		if ($endAt) {
			$model = $model->where('create_at', '<=', $endAt);
		}

		if (!empty($status)) {
			if (is_array($status)) {
				$model = $model->whereIn('status', $status);
			} else {
				$model = $model->where('status', $status);
			}
		}
		$model = self::__orders($model, $orders);

		if ($pageSize > 0) {
			$model->paginate($pageSize, array('*'), 'pageNumber', $pageNumber);
		}

		return $model->get();
	}
}