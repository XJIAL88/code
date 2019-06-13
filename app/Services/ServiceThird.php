<?php

namespace App\Services;

use App\Bases\BaseServiceApi;
use App\Manages\ManageActivity;


/**
 * 业务 账户管理
 * User: Administrator
 * Date: 2016/7/28
 * Time: 16:06
 */
class ServiceThird extends BaseServiceApi
{
	static $name = 'third';
	static $errorArray = array(
		1000 => '',
		1001 => '审核结果状态不对',
		1002 => '审核驳回评论不能为空状态不对',
		1003 => '活动不存在',
		1004 => '活动状态不是审批中',
		1005 => '活动状态修改失败',
		1006 => '活动删除失败',
	);


	/**
	 * 活动申请审核结果
	 * @param string $comment
	 * @param string $number
	 * @param int $status
	 * @return array
	 */
	static function applyApprovalResult($number = '', $comment = '', $status = 0)
	{
		if (!($status == 2 || $status == 3)) {
			return self::returnError(self::$name . '1001', self::$errorArray[1001]);
		}
		$activity = self::arObject2Array(ManageActivity::getWith(self::getConnection(), 'number', $number));
		if (empty($activity)) {
			return self::returnError(self::$name . '1003', self::$errorArray[1003]);
		}
		if ($activity['status'] != 1) {
			return self::returnError(self::$name . '1004', self::$errorArray[1004]);
		}

		if ($status == 2) {
			$result = ManageActivity::update(self::getConnection(), $activity['id'], ['status' => 2]);
			if (self::checkUpdateResultAndLeastOne($result) === false) {
				return self::returnError(self::$name . '1005', self::$errorArray[1005]);
			}
		}
		if ($status == 3) {
			$result = ManageActivity::update(self::getConnection(), $activity['id'], ['deleted' => 1]);
			if (self::checkDeleteResultAndLeastOne($status) === false) {
				return self::returnError(self::$name . '1006', self::$errorArray[1006]);
			}
		}

		return self::returnSuccess($result);

	}


}