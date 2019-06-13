<?php

namespace App\Services;

use App\Bases\BaseComponent;
use App\Bases\BaseKernel;
use App\Bases\BaseServiceAdmin;
use App\Bases\BaseServiceCzy;
use App\Component\ComponentCzyResource;
use App\Manages\ManageActivity;
use App\Manages\ManageActivityAward;
use App\Manages\ManageAward;
use App\Manages\ManageAwardChangeLog;


/**
 * 业务 账户管理
 * User: Administrator
 * Date: 2016/7/28
 * Time: 16:06
 */
class ServiceAdminActivity extends BaseServiceAdmin
{
	static $name = 'activity';
	static $errorArray = array(
		1000 => '',
		1001 => '活动名称不能为空',
		1002 => '活动开始时间不能为空',
		1003 => '活动结束时间不能为空',
		1004 => '活动范围不能为空',
		1005 => '添加活动失败',
		1006 => '申请活动失败',

		1007 => '活动id不能为空',
		1008 => '获取活动失败',
		1009 => '活动不存在',
		1010 => '活动状态不能配置奖品',
		1011 => '删除原奖项失败',
		1012 => '活动此状态下不能删除',
		1013 => '活动删除失败',
		1014 => '活动没有配置奖项',
		1015 => '活动已结束',
		1016 => '上架失败',
		1017 => '活动此状态下不能下架',
		1018 => '活动已删除',
		1019 => '活动申请资源不能为空',
		1020 => '活动类型不能为空',
		1021 => '自动失效等级记录失败',
		1022 => '中奖概率不能为空',
		1023 => '中奖概率设置错误',
		1024 => '活动编号已存在',
		1025 => '活动规则不能为空',
		1026 => '时间段起始时间不能大于结束时间',
		1027 => '时间段不能有交叉',
		1028 => '配置变更记录失败',
		1029 => '活动不是已下架状态',
		1030 => '活动不是已失效状态',
		1031 => '活动释放资源失败',
		1032 => '活动释放资源状态修改失败',
	);

	////////////////////////////////////////////          单条数据          ////////////////////////////////////////////

	/**
	 * 获取账户
	 * @param int $id
	 * @return array
	 */
	static function activityGet($id = 0)
	{
		if (empty($id)) {
			return self::returnError(self::$name . '1007', self::$errorArray[1007]);
		}
		$activity = self::arObject2Array(ManageActivity::get(self::getConnection(), $id));
		if (empty($activity)) {
			return self::returnError(self::$name . '1008', self::$errorArray[1008]);
		}
		$activity['time'] = explode(',', $activity['time']);
		$activity['award'] = [];
		$activity['changeLog'] = [];
		$award = self::arObjects2Array(ManageActivityAward::getList(self::getConnection(), ['activity_id' => $id], ['level' => 'asc']));
		if (!empty($award)) {
			foreach ($award as $index => $item) {
				$award[$index]['rate'] = explode(',', $item['rate']);
			}
			$activity['award'] = $award;
		}
		$changeLog = ManageAwardChangeLog::getList(self::getConnection(), ['activity_id' => $id], ['id' => 'asc']);
		if (!empty($changeLog)) {
			$activity['changeLog'] = $changeLog;
		}

		return self::returnSuccess($activity);

	}


	////////////////////////////////////////////          多条数据          ////////////////////////////////////////////

	/**
	 * 获取活动列表
	 * @param string $name
	 * @param string $number
	 * @param int $startAt
	 * @param int $endAt
	 * @param string $groupId
	 * @param int $new
	 * @param int $status
	 * @param int $pageSize
	 * @param int $pageNumber
	 * @return array
	 */
	static function activityGetList($name = '', $number = '', $startAt = 0, $endAt = 0, $groupId = '', $new = 0, $status = [], $search = '', $pageSize = 20, $pageNumber = 1)
	{
		$where['deleted'] = 2;
		if (!empty($search)) {
			if (is_numeric($search)) {
				$where['number'] = array('like', '%' . $search . '%');
			} else {
				$where['name'] = array('like', '%' . $search . '%');
			}
		}
		if (!empty($name)) {
			$where['name'] = array('like', '%' . $name . '%');
		}
		if (!empty($number)) {
			$where['number'] = array('like', '%' . $number . '%');
		}
		if ($startAt) {
			$where[] = array('start_at', '>=', intval($startAt));
		}
		if ($endAt) {
			$where[] = array('end_at', '<=', intval($endAt));
		}
		if (!empty($groupId)) {
			$where['groupId'] = $groupId;
		}
		if ($new) {
			$where['new'] = intval($new);
		}
		if ($status) {
			$where['status'] = array('IN', $status);
		}
		$count = ManageActivity::getCount(self::getConnection(), $where);
		$data = self::arObjects2Array(ManageActivity::getList(self::getConnection(), $where, [], $pageSize, $pageNumber));

		return self::returnSuccess([
			'dataCount' => $count,
			'dataList' => $data
		]);

	}

	////////////////////////////////////////////          添加数据          ////////////////////////////////////////////

	/**
	 * 创建活动
	 * @param string $adminName
	 * @param string $name
	 * @param int $type
	 * @param int $startAt
	 * @param int $endAt
	 * @param string $groupId
	 * @param string $gorupTitle
	 * @param int $numberDaily
	 * @param int $numberTotal
	 * @param int $new
	 * @param int $newDaylimit
	 * @param string $desc
	 * @param array $resource
	 * @return array
	 */
	static function activityInsert($adminName = '', $name = '', $type = 1, $startAt = 0, $endAt = 0, $numberDaily = 0, $numberTotal = 0, $new = 0, $newDaylimit = 0, $rule = '', $desc = '', $resource = [])
	{
		if (empty($name)) {
			return self::returnError(self::$name . '1001', self::$errorArray[1001]);
		}
		if (empty($type)) {
			return self::returnError(self::$name . '1001', self::$errorArray[1001]);
		}
		if (empty($startAt)) {
			return self::returnError(self::$name . '1002', self::$errorArray[1002]);
		}
		if (empty($endAt)) {
			return self::returnError(self::$name . '1003', self::$errorArray[1003]);
		}
		if (empty($rule)) {
			//return self::returnError(self::$name . '1025', self::$errorArray[1025]);
		}
		if (empty($resource)) {
			return self::returnError(self::$name . '1019', self::$errorArray[1019]);
		}

		//生成活动id
		$lastActivity = self::arObject2Array(ManageActivity::getWithWheres(self::getConnection(), [], ['id' => 'desc']));
		if (empty($lastActivity)) {
			$number = '0001';
		} else {

			$number = intval(substr($lastActivity['number'], -4));
			$number++;
			//每天重新计数
			$date = intval(substr($lastActivity['number'], 0, 8));
			if ($date != date('Ymd')) {
				$number = '0001';
			}
		}
		$number = date('Ymd') . str_pad($number, 4, '0', STR_PAD_LEFT);

		$data = [
			'name' => $name,
			'type' => $type,
			'number' => $number,
			'start_at' => $startAt,
			'end_at' => $endAt,
			'number_daily' => $numberDaily,
			'number_total' => $numberTotal,
			'new' => $new,
			'new_daylimit' => $newDaylimit,
			'rule' => $rule,
			'status' => 1,
			'desc' => $desc,
		];

		$count = ManageActivity::getCount(self::getConnection(), ['number' => $number]);
		if ($count) {
			return self::returnError(self::$name . '1024', self::$errorArray[1024]);
		}
		//添加活动信息
		$result = ManageActivity::insert(self::getConnection(), $data);
		if (self::checkInsertResult($result) === false) {
			return self::returnError(self::$name . '1005', self::$errorArray[1005]);
		}

		//申请活动
		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return self::returnError(self::$name . '1000', $token["message"]);
		}

		$token = $token["data"];
		$appName = '彩之云';
		$userName = $adminName;
		$orderNumber = $data['number'];
		$actName = $data['name'];
		$callbackUrl = $_ENV['PROJECT_apiDomain'] . 'third/applyApprovalResult';
		$applyResult = ComponentCzyResource::zyglptApplyActivityResource($appName, $userName, $orderNumber, $actName, $callbackUrl, $resource, $token);
		if (!isset($applyResult['code'])) {
			ManageActivity::update(self::getConnection(), $result, ['deleted' => 1]);
			return self::returnError(self::$name . '1006', self::$errorArray[1006]);
		}
		if (isset($applyResult['code']) && $applyResult['code'] !== 0) {
			ManageActivity::update(self::getConnection(), $result, ['deleted' => 1]);
			return self::returnError($applyResult['code'], $applyResult['message']);
		}

		return self::returnSuccess($result);

	}

	////////////////////////////////////////////          编辑数据          ////////////////////////////////////////////

	/**
	 * 奖项配置
	 * @param string $userName
	 * @param int $id
	 * @param array $level
	 * @param array $time
	 * @param array $resourceArray
	 * @return array|mixed
	 */
	static function activityUpdate($userName = '', $id = 0, $level = [], $time = [], $resourceArray = [])
	{
		if (empty($id)) {
			return self::returnError(self::$name . '1007', self::$errorArray[1007]);
		}
		$activity = self::arObject2Array(ManageActivity::get(self::getConnection(), $id));
		if (empty($activity)) {
			return self::returnError(self::$name . '1009', self::$errorArray[1009]);
		}
		if ($activity['deleted'] == 1) {
			return self::returnError(self::$name . '1018', self::$errorArray[1018]);
		}
		if (!in_array($activity['status'], [2, 3, 4])) {
			return self::returnError(self::$name . '1010', self::$errorArray[1010]);
		}

		//检测时间段是否有重叠
		$timeTemp = 0;
		foreach ($time as $k => $v) {
			//时间段数组
			$v = explode('-', str_replace(':', '', $v));
			if ($v[0] > $v[1]) {
				return self::returnError(self::$name . '1026', self::$errorArray[1026]);
			}
			if ($v[0] < $timeTemp) {
				return self::returnError(self::$name . '1027', self::$errorArray[1027]);
			}
			$timeTemp = $v[1];
		}
		return self::transaction(function () use ($userName, $id, $activity, $resourceArray, $level, $time) {
			//添加活动奖项
			$resourceArray = self::arObjects2Array($resourceArray);
			//检测配置是否有变化
			$log = self::checkAwardChange($userName, $id, $time, $resourceArray, $activity);
			if ($log['code']) {
				return self::returnError($log['code'], $log['message']);
			}
			//删除原奖项
			$delResult = ManageActivityAward::deleteWithWheres(self::getConnection(), ['activity_id' => $id]);
			if (self::checkDeleteResult($delResult) === false) {
				return self::returnError(self::$name . '1011', self::$errorArray[1011]);
			}

			foreach ($resourceArray as $index => $item) {
				$resourceData[$index] = [
					'activity_id' => $id,
					'activity_type' => $activity['type'],
					'award_id' => $item['awardId'],
					'name' => $item['name'],
					"level" => $item['level'],
					"category_id" => $item['categoryId'],
					"category" => $item['category'],
					'resource_number' => $item['resourceNumber'],
					"number" => $item['number'],
					"personal_daily_number" => $item['personalDailyNumber'],
					'all_daily_number' => $item['allDailyNumber'],
					'rate' => implode(',', $item['rate']),
					'create_at' => time(),
				];
			}
			$result = ManageActivityAward::insertAll(self::getConnection(), $resourceData);
			if ($result !== true) {
				return self::returnError(self::$name . '1014', self::$errorArray[1014]);
			}

			//自动失效等级回写
			$level = implode(',', $level);
			$time = implode(',', $time);

			if ($activity['startAt'] > time()) {
				$status = 3;
			} else {
				$status = 4;
			}
			$updateResult = ManageActivity::update(self::getConnection(), $activity['id'], ['level' => $level, 'time' => $time, 'status' => $status]);
			if (self::checkUpdateResult($updateResult) === false) {
				return self::returnError(self::$name . '1021', self::$errorArray[1021]);
			}

			return self::returnSuccess($result);
		}, function (\Exception $e) {
			return self::returnError(self::$name . '1000', $e->getMessage());
		});

	}



	////////////////////////////////////////////          删除数据          ////////////////////////////////////////////

	/**
	 * 活动删除
	 * @param int $id
	 * @return array
	 */
	static function activityDelete($id = 0)
	{
		if (empty($id)) {
			return self::returnError(self::$name . '1007', self::$errorArray[1007]);
		}
		$activity = self::arObject2Array(ManageActivity::get(self::getConnection(), $id));
		if (empty($activity)) {
			return self::returnError(self::$name . '1009', self::$errorArray[1009]);
		}
		if (in_array($activity['status'], [1, 3, 4])) {
			return self::returnError(self::$name . '1012', self::$errorArray[1012]);
		}
		$update = ManageActivity::update(self::getConnection(), $id, ['deleted' => 1]);
		if (self::checkUpdateResultAndLeastOne($update) === false) {
			return self::returnError(self::$name . '1013', self::$errorArray[1013]);
		}
		return self::returnSuccess($update);
	}

	/**
	 * 活动上下架
	 * @param int $id
	 * @param int $type
	 * @return array
	 */
	static function activityChangeStatus($id = 0, $type = 0)
	{
		if (empty($id)) {
			return self::returnError(self::$name . '1007', self::$errorArray[1007]);
		}
		$activity = self::arObject2Array(ManageActivity::get(self::getConnection(), $id));
		if (empty($activity)) {
			return self::returnError(self::$name . '1009', self::$errorArray[1009]);
		}
		if ($activity['deleted'] == 1) {
			return self::returnError(self::$name . '1018', self::$errorArray[1018]);
		}

		if ($type == 1) {
			//上架
			if ($activity['status'] != 5) {
				return self::returnError(self::$name . '1029', self::$errorArray[1029]);
			}
			$award = self::arObjects2Array(ManageActivityAward::getList(self::getConnection(), ['activity_id' => $id]));
			if (empty($award)) {
				return self::returnError(self::$name . '1014', self::$errorArray[1014]);
			}
			if ($activity['endAt'] < time()) {
				return self::returnError(self::$name . '1015', self::$errorArray[1015]);
			}
			//即将开始
			$status = 3;
			if ($activity['startAt'] < time()) {
				$status = 4;
			}
			$update = ManageActivity::update(self::getConnection(), $id, ['status' => $status]);
			if (self::checkUpdateResultAndLeastOne($update) === false) {
				return self::returnError(self::$name . '1015', self::$errorArray[1015]);
			}
			return self::returnSuccess($update);

		} elseif ($type == 2) {
			//下架
			if (!in_array($activity['status'], [3, 4])) {
				return self::returnError(self::$name . '1017', self::$errorArray[1017]);
			}
			$update = ManageActivity::update(self::getConnection(), $id, ['status' => 5]);
			if (self::checkUpdateResultAndLeastOne($update) === false) {
				return self::returnError(self::$name . '1015', self::$errorArray[1015]);
			}
			return self::returnSuccess($update);
		}
	}

	/**
	 * 活动上下架
	 * @param int $id
	 * @param int $type
	 * @return array
	 */
	static function activityFreeResource($id = 0)
	{
		if (empty($id)) {
			return self::returnError(self::$name . '1007', self::$errorArray[1007]);
		}
		$activity = self::arObject2Array(ManageActivity::get(self::getConnection(), $id));
		if (empty($activity)) {
			return self::returnError(self::$name . '1009', self::$errorArray[1009]);
		}
		if ($activity['status'] != 6) {
			return self::returnError(self::$name . '1030', self::$errorArray[1030]);
		}

		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return self::returnError(self::$name . '1000', $token['message']);
		}
		$token = $token["data"];

		//资源平台释放资源
		$result = ComponentCzyResource::zyglptActivityEndFreeResource($token, $activity['number']);
		if (!isset($result['code']) || (isset($result['code']) && $result['code'] !== 0)) {
			return self::returnError(self::$name . '1031', self::$errorArray[1031]);
		}

		//回写活动状态
		$statusResult = ManageActivity::update(self::getConnection(), $activity['id'], ['free_resource' => 1]);
		if (self::checkUpdateResultAndLeastOne($statusResult) === false){
			return self::returnError(self::$name . '1032', self::$errorArray[1032]);
		}

		return self::returnSuccess(true);

	}


	/**
	 * 获取资源分类
	 * @param string $title
	 * @param int $status
	 * @param int $startTime
	 * @param int $endTime
	 * @param int $pageSize
	 * @param int $pageNumber
	 * @return array
	 */
	static function resourceCategoryList($title = '', $status = 0, $startTime = 0, $endTime = 0, $pageSize = 20, $pageNumber = 1)
	{
		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return self::returnError(self::$name . '1000', $token["message"]);
		}
		$token = $token["data"];

		return ComponentCzyResource::zyglptResourceCategoryList($token, $title, $status, $startTime, $endTime, $pageSize, $pageNumber);
	}

	/**
	 * 获取资源列表
	 * @param string $category
	 * @param int $categoryId
	 * @param string $platform
	 * @param string $name
	 * @param int $status
	 * @param int $grantType
	 * @param int $callType
	 * @param int $pageSize
	 * @param int $pageNumber
	 * @return array
	 */
	static function resourceGetList($category = '', $categoryId = 0, $platform = '', $name = '', $status = 0, $grantType = 0, $callType = 0, $pageSize = 20, $pageNumber = 1)
	{
		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return self::returnError(self::$name . '1000', $token["message"]);
		}
		$token = $token["data"];

		return ComponentCzyResource::zyglptResourceGetList($token, $category, $categoryId, $platform, $name, $status, $grantType, $callType, $pageSize, $pageNumber);
	}

	/**
	 * 获取活动的资源明细
	 * @param string $orderNumber
	 * @return array
	 */
	static function activityResourceDetail($orderNumber = '', $search = '', $categoryId = 0, $pageSize = 10, $pageNumber = 1)
	{
		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return self::returnError(self::$name . '1000', $token["message"]);
		}
		$token = $token["data"];

		$result = ComponentCzyResource::zyglptActivityResourceDetail($token, $orderNumber, $search, $categoryId, $pageSize, $pageNumber);

		if (isset($result['code']) && $result['code'] === 0) {
			$datalist = $result['data']['dataList'];

			foreach ($datalist as $index => $item) {
				$datalist[$index]['effectiveTime'] = 0;
				$datalist[$index]['freeze'] = 0;

				if (isset($activityArr[$item['activityNumber']])) {
					$activity = $activityArr[$item['activityNumber']];
				} else {
					$activity = self::arObject2Array(ManageActivity::getWith(self::getConnection(), 'number', $item['activityNumber']));
					if (empty($activity)) {
						continue;
					}
					$award = self::arObjects2Array(ManageActivityAward::getList(self::getConnection(), ['activity_id' => $activity['id']], ['level' => 'asc']));
					$activity['award'] = $award;
					$activityArr[$item['activityNumber']] = $activity;
				}
				if (!empty($activity)) {
					$datalist[$index]['effectiveTime'] = $activity['endAt'];
					if (is_array($activity['award'])) {
						foreach ($activity['award'] as $k => $v) {
							//获取资源id
							$resourceAward = self::arObject2Array(ManageAward::get(self::getConnection(), $v['awardId']));
							if (empty($resourceAward)) {
								continue;
							}
							//活动申请资源 冻结 与奖品资源对应 的资源
							if ($resourceAward['resourceId'] == $item['resourceId']) {
								$freeze = $v['resourceNumber'] * $v['number'];
								$datalist[$index]['freeze'] = $freeze;
								break;
							}
						}
					}
				}
			}
			$result['data']['dataList'] = $datalist;
		}

		return $result;
	}

	/**
	 * 检测配置是否变更
	 * @param $userName
	 * @param $id
	 * @param $time
	 * @param $resourceArray
	 * @return array
	 */
	static function checkAwardChange($userName, $id, $time, $resourceArray, $activity)
	{
		$logResult = true;
		$checkField = [
			'number' => '发放数量/金额',
			'personalDailyNumber' => '每日每人数量上限',
			'allDailyNumber' => '每日全部数量上限',
			'time' => '时段',
			'rate' => '概率',

		];
		$award = self::arObjects2Array(ManageActivityAward::getList(self::getConnection(), ['activity_id' => $id], ['level' => 'asc']));
		if (!empty($award)) {
			$change = [];
			$beforeTime = $activity['time'];
			$afterTime = implode(',', $time);
			if ($beforeTime != $afterTime) {
				$change[] = [
					'activity_id' => $id,
					'activity_award_id' => 0,
					'name' => '',
					'field' => $checkField['time'],
					'before' => $beforeTime,
					'after' => $afterTime,
					'operator' => $userName,
					'create_at' => time(),
				];
			}
			foreach ($checkField as $index => $item) {
				if ($index == 'time') {
					continue;
				}
				foreach ($award as $k => $v) {
					$getValue = $resourceArray[$k][$index];
					if ($index == 'rate') {
						$getValue = implode(',', $resourceArray[$k][$index]);
					}
					if ($v[$index] != $getValue) {
						$change[] = [
							'activity_id' => $id,
							'activity_award_id' => $v['id'],
							'name' => $v['name'],
							'field' => $item,
							'before' => $v[$index],
							'after' => $getValue,
							'operator' => $userName,
							'create_at' => time(),
						];
					}
				}
			}
			if (!empty($change)) {
				$logResult = ManageAwardChangeLog::insertAll(self::getConnection(), $change);
				if (!$logResult) {
					return self::returnError(self::$name . '1011', self::$errorArray[1011]);
				}
			}
		}
		return self::returnSuccess($logResult);
	}


	/**
	 * 检测活动是否失效
	 */
	static function jobExecutionActivity()
	{
		BaseKernel::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionActivity-start/ 检测活动开始', 'jobExecutionActivity');
		$where = [];
		$where['end_at'] = array('<=', time());
		$where['status'] = array('!=', 6);
		$activity = self::arObjects2Array(ManageActivity::getList(self::getConnection(), $where));

		//ice微服务token
		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return 'error';
		}
		$token = $token["data"];

		if (!empty($activity)) {
			foreach ($activity as $index => $item) {
				$updateResult = ManageActivity::update(self::getConnection(), $item['id'], ['status' => 6]);
				if (self::checkUpdateResult($updateResult) === false) {
					BaseKernel::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel--error/ 活动自动失效处理失败' . json_encode($item, JSON_UNESCAPED_UNICODE), 'jobExecutionActivity');
				}
				$result = ComponentCzyResource::zyglptActivityEndFreeResource($token, $item['number']);
				if (!isset($result['code']) || (isset($result['code']) && $result['code'] !== 0)) {
					BaseKernel::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel--error/ 资源平台释放资源失败' . json_encode($result, JSON_UNESCAPED_UNICODE), 'jobExecutionActivity');
				} else {
					ManageActivity::update(self::getConnection(), $item['id'], ['free_resource' => 1]);
				}

				BaseKernel::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionActivity--success/ 活动自动失效处理成功' . json_encode($item, JSON_UNESCAPED_UNICODE), 'jobExecutionActivity');
			}
		} else {
			BaseKernel::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionActivity/ 没有需要处理的活动', 'jobExecutionActivity');
		}
		BaseKernel::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionActivity-end/ 检测活动结束', 'jobExecutionActivity');
	}


//	/**
//	 * 申请抽奖资格
//	 * @param $number
//	 * @param $mobile
//	 * @return array
//	 */
//	static function lotteryQualificationsApply($number,$mobile)
//	{
//		$token = BaseServiceCzy::getAppToken();
//		if ($token["code"] !== 0) {
//			return self::returnError(self::$name . '1000', $token['message']);
//		}
//		$token = $token["data"];
//
//		//资源平台释放资源
//		return  ComponentCzyResource::hdglptLotteryQualificationsApply($token, $number,$mobile);
//	}
//	/**
//	 * 抽奖
//	 * @param $number
//	 * @param $mobile
//	 * @return array
//	 */
//	static function getLotteryResult($number,$mobile)
//	{
//		$token = BaseServiceCzy::getAppToken();
//		if ($token["code"] !== 0) {
//			return self::returnError(self::$name . '1000', $token['message']);
//		}
//		$token = $token["data"];
//
//		//资源平台释放资源
//		return  ComponentCzyResource::hdglptGetLotteryResult($token, $number,$mobile);
//	}
//	/**
//	 * 申请资格&抽奖
//	 * @param $number
//	 * @param $mobile
//	 * @return array
//	 */
//	static function qualificationsApplyAndGetLotteryResult($number,$mobile)
//	{
//		$token = BaseServiceCzy::getAppToken();
//		if ($token["code"] !== 0) {
//			return self::returnError(self::$name . '1000', $token['message']);
//		}
//		$token = $token["data"];
//
//		//资源平台释放资源
//		return  ComponentCzyResource::hdglptQualificationsApplyAndGetLotteryResult($token, $number,$mobile);
//	}

}