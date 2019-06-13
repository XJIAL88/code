<?php

namespace App\Services;

use App\Bases\BaseServiceAdmin;
use App\Bases\BaseServiceApp;
use App\Bases\BaseServiceCzy;
use App\Component\ComponentCzy;
use App\Component\ComponentCzyResource;
use App\Component\ComponentRandom;
use App\Manages\ManageActivity;
use App\Manages\ManageActivityAward;
use App\Manages\ManageAward;
use App\Manages\ManageLotteryQualifications;
use App\Manages\ManageLotteryRecord;
use App\Manages\ManageUser;

/**
 * 业务 资源--微服务
 * User: Administrator
 * Date: 2016/7/28
 * Time: 16:06
 */
class ServiceAdminMicroservice extends BaseServiceAdmin
{
	static $name = 'microLottery';
	static $errorArray = array(
		1000 => '',
		1001 => '抽奖记录id不能为空',
		1002 => '活动ID不能为空',
		1003 => '用户手机号不能为空',
		1004 => '用户抽奖资格添加失败',
		1005 => '活动不存在',
		1006 => '活动未开始或已结束',
		1007 => '用户没有抽奖资格',
		1008 => '当前时间段不能抽奖',
		1009 => '中奖记录失败',
		1010 => '抽奖资格回写失败',
		1011 => '奖品已抽取完毕',
		1012 => '减少奖品数量失败',
		1013 => '更新奖品数据失败',
		1014 => '此奖品今日您的份额已抽完',
		1015 => '此奖品今日已抽完',
		1016 => '彩之云用户不存在',
		1017 => '用户添加失败',
		1018 => '此次活动申请抽奖次数已满,不能申请资格',
		1019 => '用户今日抽奖次数已满,不能再抽奖',
		1020 => '用户此次活动抽奖次数已满,不能再抽奖',
		1021 => '系统繁忙,请重新抽奖',
		1022 => '用户不存在',
		1023 => '今日奖品份额已抽完',
		1024 => '奖品抽完,活动下架失败',
		1025 => '活动为新人专享',
		1026 => '奖品已发放数量记录失败',

	);

	/**
	 * 用户申请抽奖资格
	 * @param string $number
	 * @param string $mobile
	 * @return array
	 */
	static function lotteryQualificationsApply($number = '', $mobile = '')
	{
		return self::transaction(function () use ($number, $mobile) {
			return self::sysLotteryQualificationsApply($number, $mobile);
		}, function (\Exception $e) {
			return self::returnError(self::$name . '1000', $e->getMessage());
		});
	}

	/**
	 * 用户抽奖
	 * @param string $number
	 * @param string $mobile
	 * @return array|mixed
	 */
	static function getLotteryResult($number = '', $mobile = '')
	{

		return self::transaction(function () use ($number, $mobile) {
			return self::sysGetLotteryResult($number, $mobile);
		}, function (\Exception $e) {
			return self::returnError(self::$name . '1000', $e->getMessage());
		});

	}

	/**
	 * 用户申请资格,同时抽奖
	 * @param string $number
	 * @param string $mobile
	 * @return array|mixed
	 */
	static function qualificationsApplyAndGetLotteryResult($number = '', $mobile = '')
	{
		return self::transaction(function () use ($number, $mobile) {
			//申请抽奖资格
			$applyResult = self::sysLotteryQualificationsApply($number, $mobile);
			if (!isset($applyResult['code']) || (isset($applyResult['code']) && $applyResult['code'] !== 0)) {
				return $applyResult;
			}
			//抽奖 并返回抽奖结果
			return self::sysGetLotteryResult($number, $mobile);

		}, function (\Exception $e) {
			return self::returnError(self::$name . '1000', $e->getMessage());
		});
	}

	/**
	 * 系统申请抽奖资格
	 * @param string $number
	 * @param string $mobile
	 * @return array
	 */
	static function sysLotteryQualificationsApply($number = '', $mobile = '')
	{
		if (empty($number)) {
			return self::returnError(self::$name . '1002', self::$errorArray[1002]);
		}
		if (empty($mobile)) {
			return self::returnError(self::$name . '1003', self::$errorArray[1003]);
		}

		$user = self::arObject2Array(ManageUser::getWith(self::getConnection(), 'czy_mobile', $mobile));
		if (!empty($user)) {
			$userId = $user['id'];
		} else {
			//彩之云用户信息
			$token = BaseServiceCzy::getAppToken();
			if ($token['code']) {
				return self::returnError(self::$name . '1000', $token['message']);
			}
			$token = $token['data'];
			// 获取彩之云用户信息（调用彩之云API）
			$czyUserInfo = ComponentCzy::customerGetinfo($token, $mobile);
			$czyUserInfo2 = ComponentCzy::customerGetinfo2($token, $mobile);

			if (!isset($czyUserInfo['code']) || (isset($czyUserInfo['code']) && $czyUserInfo['code'] !== 0)) {
				return self::returnError(self::$name . '1016', self::$errorArray[1016]);
			}
			if (!isset($czyUserInfo2['code']) || (isset($czyUserInfo2['code']) && $czyUserInfo2['code'] !== 0)) {
				return self::returnError(self::$name . '1016', self::$errorArray[1016]);
			}
			$userData = array(
				'czy_uuid' => $czyUserInfo['data']['uuid'],
				'czy_nickname' => $czyUserInfo['data']['name'],
				'czy_portrait_url' => $czyUserInfo['data']['portrait'],
				'czy_mobile' => $czyUserInfo['data']['mobile'],
				'czy_gender' => $czyUserInfo['data']['gender'],
				'czy_create_at' => $czyUserInfo2['data']['create_time'],
				'status' => 1,
			);

			$userId = ManageUser::insert(self::getConnection(), $userData);
			if (self::checkInsertResult($userId) === false) {
				return self::returnError(1104, self::$errorArray[1104]);
			}
		}

		//活动信息
		$activity = self::arObject2Array(ManageActivity::getWith(self::getConnection(), 'number', $number));
		if (empty($activity)) {
			return self::returnError(self::$name . '1005', self::$errorArray[1005]);
		}

		$where = [];
		$where['activity_number'] = $number;
		$where['user_id'] = $userId;

		//用户活动抽奖总次数
		if (!empty($activity['numberTotal'])) {
			$totalNumber = ManageLotteryQualifications::getCount(self::getConnection(), $where);
			if ($totalNumber >= $activity['numberTotal']) {
				return self::returnError(self::$name . '1018', self::$errorArray[1018]);
			}
		}

		$data = [
			'activity_number' => $number,
			'user_id' => $userId,
			'status' => 1,
		];

		$result = ManageLotteryQualifications::insert(self::getConnection(), $data);
		if (self::checkInsertResult($result) === false) {
			return self::returnError(self::$name . '1004', self::$errorArray[1004]);
		}

		return self::returnSuccess($result);
	}

	/**
	 * 系统抽奖
	 * @param string $number
	 * @param string $mobile
	 * @return array|mixed
	 */
	static function sysGetLotteryResult($number = '', $mobile = '')
	{
		if (empty($number)) {
			return self::returnError(self::$name . '1002', self::$errorArray[1002]);
		}
		if (empty($mobile)) {
			return self::returnError(self::$name . '1003', self::$errorArray[1003]);
		}

		//获取用户信息
		$userInfo = self::arObject2Array(ManageUser::getWith(self::getConnection(), 'czy_mobile', $mobile));
		if (empty($userInfo)) {
			return self::returnError(self::$name . '1022', self::$errorArray[1022]);
		}
		$userId = $userInfo['id'];

		//活动信息
		$activity = self::arObject2Array(ManageActivity::getWith(self::getConnection(), 'number', $number));
		if (empty($activity)) {
			return self::returnError(self::$name . '1005', self::$errorArray[1005]);
		}
		//新人专享
		if ($activity['new']) {
			//新人有效时间
			$time = strtotime(date('Y-m-d', strtotime('-' . $activity['newDaylimit'] . ' days')));
			if ($userInfo['czyCreateAt'] < $time) {
				return self::returnError(self::$name . '1025', self::$errorArray[1025]);
			}
		}

		//检测活动状态  活动状态为进行时,且结束时间大于当前时间为有效活动
		if (!($activity['status'] == 4 && $activity['endAt'] > time())) {
			return self::returnError(self::$name . '1006', self::$errorArray[1006]);
		}


		//检测用户是否有抽奖资格
		$qualificationsResult = self::checkLotteryQualifications2($number, $userId, $activity);
		if ($qualificationsResult['lottery'] === false) {
			$errorCode = [1 => 1007, 2 => 1019, 3 => 1020];
			return self::returnError(self::$name . $errorCode[$qualificationsResult['lotteryType']], self::$errorArray[$errorCode[$qualificationsResult['lotteryType']]]);
		}

		//活动奖品
		$activityAward = self::arObjects2Array(ManageActivityAward::getList(self::getConnection(), ['activity_id' => $activity['id']]));
		//抽完的奖项不在参与抽奖
		$levelOver = [];
		foreach ($activityAward as $key => $item) {
			//所有奖品抽完了
			if ($item['number'] <= $item['usedNumber']) {
				$levelOver[] = $item['level'];
				unset($activityAward[$key]);
			}
			//奖品今日限额抽完了 个人限额和奖品限额
			$where_limit = [];
			$where_limit['activity_id'] = $activity['id'];
			$where_limit['award_id'] = $item['id'];
			$where_limit['create_at'] = array('>=', strtotime(date('Ymd')));

			//此奖品当日已抽数量
			if (!empty($item['allDailyNumber'])) {
				$winnedAllNumberToday = ManageLotteryRecord::getCount(self::getConnection(), $where_limit);
				if ($winnedAllNumberToday >= $item['allDailyNumber']) {
					unset($activityAward[$key]);
					continue;
				}
			}

			//用户今日已抽中此奖品数量
			if (!empty($item['personalDailyNumber'])) {
				$where_limit['user_id'] = $userId;
				$winnedPersonalNumberToday = ManageLotteryRecord::getCount(self::getConnection(), $where_limit);
				if ($winnedPersonalNumberToday >= $item['personalDailyNumber']) {
					unset($activityAward[$key]);
				}
			}
		}
		$activityAward = array_values($activityAward);

		$time = date('Hi');
		$periods = explode(',', $activity['time']);
		$currentTime = false;
		foreach ($periods as $index => $period) {
			$period = explode('-', str_replace(':', '', $period));
			if ($period[0] <= $time && $time <= $period[1]) {
				$currentTime = $index;
			}
		}

		//时间段不允许抽奖
		if ($currentTime === false) {
			return self::returnError(self::$name . '1008', self::$errorArray[1008]);
		}

		//份额已抽完
		if (empty($activityAward)) {
			return self::returnError(self::$name . '1023', self::$errorArray[1023]);
		}

		//奖品代号
		$str = 'abcdefghijklmnopqrstuvwxyz';
		foreach ($activityAward as $index => $item) {
			$rate = explode(',', $item['rate']);
			$rate = $rate[$currentTime];
			//奖品代号概率(数量)
			$lottery[] = [
				'str' => substr($str, $index, 1),
				'rate' => $rate * 10
			];
		}
		//抽奖字符串
		$ransStr = '';
		foreach ($lottery as $v) {
			for ($i = 0; $i < $v['rate']; $i++) {
				$ransStr .= $v['str'];
			}
		}

		//抽奖
		$winAward = self::getLotteryAwardResult($str, $ransStr, $activity, $activityAward, $userId);
		if (isset($winAward['code']) && $winAward['code'] !== 0) {
			return $winAward;
		}

		//默认中奖, 未中奖(谢谢参与等) 看需求处理
		$ifAward = 1;

		//中奖记录
		$recordData = [
			'activity_id' => $activity['id'],
			'activity_name' => $activity['name'],
			'activity_number' => $activity['number'],
			'user_id' => $userId,
			'user_name' => $userInfo['czyNickname'],
			'user_mobile' => $userInfo['czyMobile'],
			'user_address' => $userInfo['address'],
			'award' => $ifAward,
			'award_id' => $winAward['id'],
			'award_name' => $winAward['name'],
			'award_level' => $winAward['level'],
			'award_number' => $winAward['number'],
			'status' => 1,
		];

		$insertRecord = ManageLotteryRecord::insert(self::getConnection(), $recordData);
		if (self::checkInsertResult($insertRecord) === false) {
			return self::returnError(self::$name . '1009', self::$errorArray[1009]);
		}
		//抽奖资格状态修改
		$updateNumber = ManageLotteryQualifications::update(self::getConnection(), $qualificationsResult['extraNumberId'], ['status' => 2]);
		if (self::checkUpdateResultAndLeastOne($updateNumber) === false) {
			return self::returnError(self::$name . '1010', self::$errorArray[1010]);
		}

		//奖品使用数量加1
		$decResult = ManageActivityAward::incrementWithWheres(self::getConnection(), 'used_number', 1, ['id' => $winAward['id'], 'version' => $winAward['version']]);
		if (self::checkUpdateResultAndLeastOne($decResult) === false) {
			return self::returnError(self::$name . '1012', self::$errorArray[1012]);
		}
		//更新数据版本号加1
		$incResult = ManageActivityAward::increment(self::getConnection(), $winAward['id'], 'version', 1);
		if (self::checkUpdateResultAndLeastOne($incResult) === false) {
			return self::returnError(self::$name . '1013', self::$errorArray[1013]);
		}

		//奖品已发放数量
		$incResult = ManageAward::increment(self::getConnection(), $winAward['awardId'], 'used_number', 1);
		if (self::checkUpdateResultAndLeastOne($incResult) === false) {
			return self::returnError(self::$name . '1026', self::$errorArray[1026]);
		}

		//检测活动是否要下架	//选中几个奖品 抽完活动下架
		if (!empty($activity['level'])) {
			$ifObtained = self::lotteryLevelObtained($activity['id'], $activity['level']);
			if ($ifObtained['code']) {
				return $ifObtained;
			}
		}

		//消费资源
		$token = BaseServiceCzy::getAppToken();
		if ($token['code']) {
			return self::returnError(self::$name . '1000', $token['message']);
		}
		$token = $token['data'];
		//奖品资源信息
		$resourceAward = self::arObject2Array(ManageAward::get(self::getConnection(), $winAward['awardId']));
		$result = ComponentCzyResource::zyglptUseActivityResource($token, $activity['number'], $activity['name'], $resourceAward['resourceId'], $resourceAward['resourceNumber'], $userInfo['czyMobile'], $userInfo['czyNickname']);
		if (!isset($result['code']) || (isset($result['code']) && $result['code'] !== 0)) {
			return self::returnError($result['code'], $result['message']);
		}

		return self::returnSuccess($winAward);

	}

	/**
	 * 获取抽奖记录列表
	 * @param string $number
	 * @param int $userId
	 * @param int $award
	 * @param int $pageSize
	 * @param int $pageNumber
	 * @return array
	 */
	static function getLotteryList($number = '', $userId = 0, $award = 0, $pageSize = 10, $pageNumber = 1)
	{
		if (empty($number)) {
			return self::returnError(self::$name . '1002', self::$errorArray[1002]);
		}
		$where['activity_number'] = $number;
		$where['status'] = 1;
		if ($userId) {
			$where['user_id'] = $userId;
		}
		if ($award) {
			$where['award'] = $award;
		}
		$count = ManageLotteryRecord::getCount(self::getConnection(), $where);
		$result = self::arObjects2Array(ManageLotteryRecord::getList(self::getConnection(), $where, [], $pageSize, $pageNumber));
		return self::returnSuccess([
			'dataCount' => $count,
			'dataList' => $result,
		]);
	}


	/**
	 * 获取抽奖记录明细
	 * @param $id
	 * @return array
	 */
	static function getLotteryDetail($id)
	{
		if (empty($id)) {
			return self::returnError(self::$name . '1001', self::$errorArray[1001]);
		}
		$result = self::arObject2Array(ManageLotteryRecord::get(self::getConnection(), $id));
		return self::returnSuccess($result);
	}

	/**
	 * 抽奖地址
	 */
	static function getLotteryUrl()
	{
		$url = isset($_ENV['PROJECT_czyInterfaceLotteryUrl']) ? $_ENV['PROJECT_czyInterfaceLotteryUrl'] : '';
		return self::returnSuccess($url);
	}


	/**
	 * 抽奖资格检测(有主动送资格版本)
	 */
	static function checkLotteryQualifications($number, $userId, $activity)
	{
		//是否可抽奖
		$lottery = false;
		//抽奖资格类型1活动设置2额外申请
		$lotteryType = 0;

		$where = [];
		$where['activity_number'] = $number;
		$where['user_id'] = $userId;
		//用户活动抽奖总次数
		$totalNumber = ManageLotteryRecord::getCount(self::getConnection(), $where);
		//用户当日抽奖册数
		$where['create_at'] = array('>=', strtotime(date('Ymd')));
		$daliyNumber = ManageLotteryRecord::getCount(self::getConnection(), $where);
		//用户额外抽奖次数
		unset($where['create_at']);
		$where['status'] = 1;
		$extraNumber = self::arObject2Array(ManageLotteryQualifications::getWithWheres(self::getConnection(), $where));

		//是否有额外抽奖资格
		if (!empty($extraNumber)) {
			$lottery = true;
			$lotteryType = 2;
		}
		//每日无限次抽
		if ($activity['numberDaily'] == 0) {
			$lottery = true;
			$lotteryType = 1;
		}
		//总数无限制, 当日有限制
		if ($activity['numberDaily'] && $activity['numberTotal'] == 0) {
			//今日可抽
			if ($daliyNumber < $activity['numberTotal']) {
				$lottery = true;
				$lotteryType = 1;
			}
		}

		//每日和总数都有限制
		if ($activity['numberDaily'] && $activity['numberTotal']) {
			if (($totalNumber < $activity['numberTotal']) && ($daliyNumber < $activity['numberDaily'])) {
				$lottery = true;
				$lotteryType = 1;
			}
		}

		return [
			'lottery' => $lottery,
			'lotteryType' => $lotteryType,
			'extraNumberId' => isset($extraNumber['id']) ? $extraNumber['id'] : 0,
		];
	}

	/**
	 * 抽奖资格检测(无主动送资格版本)
	 */
	static function checkLotteryQualifications2($number, $userId, $activity)
	{
		//是否可抽奖 默认可抽奖
		$lottery = true;
		//不能抽奖的类型1没有抽奖资格2当日抽奖次数已满3活动抽奖资格已满
		$lotteryType = 0;

		$where = [];
		$where['activity_number'] = $number;
		$where['user_id'] = $userId;

		//用户是否可抽奖
		$where['status'] = 1;
		$extraNumber = self::arObject2Array(ManageLotteryQualifications::getWithWheres(self::getConnection(), $where));

		//没有抽奖资格
		if (empty($extraNumber)) {
			$lottery = false;
			$lotteryType = 1;
		}

		unset($where['status']);
		//用户活动抽奖总次数
		$totalNumber = ManageLotteryRecord::getCount(self::getConnection(), $where);
		//用户当日抽奖册数
		$where['create_at'] = array('>=', strtotime(date('Ymd')));
		$daliyNumber = ManageLotteryRecord::getCount(self::getConnection(), $where);

		if (!empty($activity['numberDaily'])) {
			if ($daliyNumber >= $activity['numberDaily']) {
				$lottery = false;
				$lotteryType = 2;
			}
		}

		if (!empty($activity['numberTotal'])) {
			if ($totalNumber >= $activity['numberTotal']) {
				$lottery = false;
				$lotteryType = 3;
			}
		}

		return [
			'lottery' => $lottery,
			'lotteryType' => $lotteryType,
			'extraNumberId' => isset($extraNumber['id']) ? $extraNumber['id'] : 0,
		];
	}

	/**
	 * 抽奖
	 * @param $str
	 * @param $ransStr
	 * @param $activityAward
	 * @param int $times
	 * @return array
	 */
	static function getLotteryAwardResult($str, $ransStr, $activity, $activityAward, $userId, $times = 0)
	{
		//抽中同一奖品超限后,可重新抽奖,但不能重抽10此以上
		if ($times > 10) {
			return self::returnError(self::$name . '1021', self::$errorArray[1021]);
		}
		//中奖代号
		$awardStr = ComponentRandom::genRandomStr(1, $ransStr);
		//中奖奖品索引
		$awardIndex = stripos($str, $awardStr);
		//中奖奖品
		$winAward = $activityAward[$awardIndex];

		//中奖奖品数量检测
		//中奖数量是否超出限制
		$overrun = 0;
		$where_winned = [];
		$where_winned['activity_id'] = $activity['id'];
		$where_winned['user_id'] = $userId;
		$where_winned['award_id'] = $winAward['id'];
		$where_winned['create_at'] = array('>=', strtotime(date('Ymd')));

		//每人当日数量
		$winnedPersonalNumberToday = ManageLotteryRecord::getCount(self::getConnection(), $where_winned);
		if (!empty($winAward['personalDailyNumber']) && $winnedPersonalNumberToday >= $winAward['personalDailyNumber']) {
			$overrun = 1;
		}
		//全部当日数量
		unset($where_winned['user_id']);
		$winnedAllNumberToday = ManageLotteryRecord::getCount(self::getConnection(), $where_winned);
		if (!empty($winAward['allDailyNumber']) && $winnedAllNumberToday >= $winAward['allDailyNumber']) {
			$overrun = 1;
		}

		if ($overrun) {
			$times++;
			return self::getLotteryAwardResult($str, $ransStr, $activity, $activityAward, $userId, $times);

		}

		if ($winAward['number'] <= $winAward['usedNumber']) {
			return self::returnError(self::$name . '1011', self::$errorArray[1011]);
		}

		return $winAward;
	}

	/**
	 * 是否有下架
	 * @param $activityId
	 * @param $activityLevel
	 * @return array
	 */
	static function lotteryLevelObtained($activityId, $activityLevel)
	{
		$result = true;
		//活动奖品
		$activityAward = self::arObjects2Array(ManageActivityAward::getList(self::getConnection(), ['activity_id' => $activityId]));

		//抽完的奖项不在参与抽奖
		$levelOver = [];
		foreach ($activityAward as $key => $item) {
			//所有奖品抽完了
			if ($item['number'] <= $item['usedNumber']) {
				$levelOver[] = $item['level'];
			}
		}
		//活动是否要下架
		$obtained = true;
		$levels = explode(',', $activityLevel);
		foreach ($levels as $level) {
			if (!in_array($level, $levelOver)) {
				//有选中奖品没抽完,不用下架
				$obtained = false;
			}
		}

		if ($obtained) {
			//活动下架
			$result = ManageActivity::update(self::getConnection(), $activityId, ['status' => 5]);
			if (self::checkUpdateResultAndLeastOne($result) === false) {
				return self::returnError(self::$name . '1024', self::$errorArray[1024]);
			}
		}

		return self::returnSuccess($result);

	}

	/**
	 * 返回成功
	 * @param array $data
	 * @return array
	 */
	static function returnSuccess($data = array())
	{
		return array(
			'code' => 0,
			'message' => 'success',
			'content' => $data,
			'contentEncrypt' => ''
		);
	}

}