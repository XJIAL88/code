<?php

namespace App\Services;

use App\Bases\BaseComponent;
use App\Bases\BaseServiceAdmin;
use App\Bases\BaseServiceCzy;
use App\Component\ComponentCzyResource;
use App\Manages\ManageActivity;
use App\Manages\ManageActivityAward;
use App\Manages\ManageAward;
use function foo\func;
use Illuminate\Support\Facades\DB;


/**
 * 活动奖品管理
 * User: Administrator
 * Date: 2016/7/28
 * Time: 16:06
 */
class ServiceAdminActivityAward extends BaseServiceAdmin
{
	static $name = 'activityAward';
	static $errorArray = array(
		1000 => '',
		1001 => '奖品所属活动不能为空',
		1002 => '奖品所属资源类型不能为空',
		1003 => '奖品所属资源不能为空',
		1004 => '奖品名称不能为空',
		1005 => '奖品图片不能为空',
		1006 => '奖品数量资源比例不能为空',
		1007 => '奖品数量不能为空',
		1008 => '奖品添加失败',

		1101 => '奖品id不能为空',
		1102 => '奖品不存在',

		1201 => '编辑奖品失败',
		1202 => '奖品已使用数量大于总数量',

		1301 => '奖品状态改变失败',
		1302 => '活动是否奖品状态改变失败',


	);

	////////////////////////////////////////////          单条数据          ////////////////////////////////////////////

	/**
	 * 获取账户
	 * @param int $id
	 * @return array
	 */
	static function activityAwardGet($id = 0)
	{
		if (empty($id)) {
			return self::returnError(self::$name . '1101', self::$errorArray[1101]);
		}
		$award = self::arObject2Array(ManageAward::get(self::getConnection(), $id));
		if (empty($award)) {
			return self::returnError(self::$name . '1008', self::$errorArray[1008]);
		}

		return self::returnSuccess($award);

	}


	////////////////////////////////////////////          多条数据          ////////////////////////////////////////////

	/**
	 * 获取奖品列表
	 * @param string $search
	 * @param int $pageSize
	 * @param int $pageNumber
	 * @return array
	 */
	static function activityAwardGetlist($search = '', $pageSize = 20, $pageNumber = 1)
	{
		//活动数量
		$count = ManageActivity::getSearchCount(self::getConnection(), $search);
		//活动列表
		$list = self::arObjects2Array(ManageActivity::getSearchGetList(self::getConnection(), $search, [], $pageSize, $pageNumber));
		if (!empty($list)) {
			foreach ($list as $k => $v) {
				$list[$k]['award'] = [];
				//活动奖品
				$activityAward = self::arObjects2Array(ManageAward::getList(self::getConnection(), ['activity_id' => $v['id']]));
				if (!empty($activityAward)) {
					$list[$k]['award'] = $activityAward;
				}
			}
		}
		return self::returnSuccess([
			'dataCount' => $count,
			'dataList' => $list
		]);

	}

	////////////////////////////////////////////          添加数据          ////////////////////////////////////////////

	/**
	 * 添加奖品
	 * @param int $activityId
	 * @param int $activityId
	 * @param int $resourceId
	 * @param string $name
	 * @param string $image
	 * @param int $resourceNumber
	 * @param int $awardNumber
	 * @return array
	 */
	static function activityAwardInsert($activityId = 0, $activityName = '', $categoryId = 0, $categoryName = '', $resourceId = 0, $resourceName = '', $name = '', $image = '', $resourceNumber = 0, $awardNumber = 0)
	{
		if (empty($activityId)) {
			return self::returnError(self::$name . '1001', self::$errorArray[1001]);
		}
		if (empty($categoryId)) {
			return self::returnError(self::$name . '1002', self::$errorArray[1002]);
		}
		if (empty($resourceId)) {
			return self::returnError(self::$name . '1003', self::$errorArray[1003]);
		}
		if (empty($name)) {
			return self::returnError(self::$name . '1004', self::$errorArray[1004]);
		}
		if (empty($image)) {
			return self::returnError(self::$name . '1005', self::$errorArray[1005]);
		}
		if (empty($resourceNumber)) {
			return self::returnError(self::$name . '1006', self::$errorArray[1006]);
		}
		if (empty($awardNumber)) {
			return self::returnError(self::$name . '1007', self::$errorArray[1007]);
		}

		$data = [
			'activity_id' => $activityId,
			'activity_name' => $activityName,
			'category_id' => $categoryId,
			'category_name' => $categoryName,
			'resource_id' => $resourceId,
			'resource_name' => $resourceName,
			'name' => $name,
			'image' => $image,
			'resource_number' => $resourceNumber,
			'award_number' => $awardNumber,
			'status' => 1,
		];

		return self::transaction(function () use ($activityId, $data) {
			//添加奖品
			$result = ManageAward::insert(self::getConnection(), $data);
			if (self::checkInsertResult($result) === false) {
				return self::returnError(self::$name . '1008', self::$errorArray[1008]);
			}

			//回写活动状态
			$activityUpdate = ManageActivity::update(self::getConnection(), $activityId, ['is_award' => 1]);
			if (self::checkUpdateResult($activityUpdate) === false) {
				return self::returnError(self::$name . '1302', self::$errorArray[1302]);
			}

			return self::returnSuccess($result);
		},function (\Exception $e){
			return self::returnError(self::$name . '1000', $e->getMessage());
		});

	}

	////////////////////////////////////////////          编辑数据          ////////////////////////////////////////////

	/**
	 * 奖品编辑
	 * @param int $id
	 * @param string $name
	 * @param string $image
	 * @param int $resourceNumber
	 * @param int $awardNumber
	 * @return array
	 */
	static function activityAwardUpdate($id = 0, $name = '', $image = '', $resourceNumber = 0, $awardNumber = 0)
	{
		if (empty($id)) {
			return self::returnError(self::$name . '1101', self::$errorArray[1101]);
		}
		if (empty($name)) {
			return self::returnError(self::$name . '1004', self::$errorArray[1004]);
		}
		if (empty($image)) {
			return self::returnError(self::$name . '1005', self::$errorArray[1005]);
		}
		if (empty($resourceNumber)) {
			return self::returnError(self::$name . '1006', self::$errorArray[1006]);
		}
		if (empty($awardNumber)) {
			return self::returnError(self::$name . '1007', self::$errorArray[1007]);
		}

		$award = self::arObject2Array(ManageAward::get(self::getConnection(), $id));
		if (empty($award)) {
			return self::returnError(self::$name . '1102', self::$errorArray[1102]);
		}
		if ($award['usedNumber'] > $awardNumber) {
			return self::returnError(self::$name . '1202', self::$errorArray[1202]);
		}

		$data = [
			'name' => $name,
			'image' => $image,
			'resource_number' => $resourceNumber,
			'award_number' => $awardNumber,
		];

		//编辑奖品
		$result = ManageAward::update(self::getConnection(), $id, $data);
		if (self::checkUpdateResult($result) === false) {
			return self::returnError(self::$name . '1201', self::$errorArray[1201]);
		}

		return self::returnSuccess($result);

	}

	/**
	 * 奖品启用/禁用
	 * @param int $id
	 * @param int $type
	 * @return array
	 */
	static function activityAwardChangeStatus($id = 0, $type = 0)
	{
		if (empty($id)) {
			return self::returnError(self::$name . '1007', self::$errorArray[1007]);
		}
		$award = self::arObject2Array(ManageAward::get(self::getConnection(), $id));
		if (empty($award)) {
			return self::returnError(self::$name . '1102', self::$errorArray[1102]);
		}
		$result = ManageAward::update(self::getConnection(), $id, ['status' => $type]);

		if (self::checkUpdateResult($result) === false) {
			return self::returnError(self::$name . '1301', self::$errorArray[1301]);
		}

		return self::returnSuccess($result);

	}


}