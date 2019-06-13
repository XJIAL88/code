<?php

namespace App\Component;

use App\Bases\BaseComponent;

/**
 * 彩之云-资源管理平台
 * User: Administrator
 * Date: 2016/7/13
 * Time: 16:44
 */
class ComponentCzyResource extends ComponentCzy
{
	/**
	 * 获取资源分类
	 * @param $token
	 * @param $title
	 * @param $status
	 * @param $startTime
	 * @param $endTime
	 * @param $pageSize
	 * @param $pageNumber
	 * @return array
	 */
	static function zyglptResourceCategoryList($token, $title, $status, $startTime, $endTime, $pageSize, $pageNumber)
	{
		$time = time();
		$url = self::__getCzyInterfaceApiUrl('v1/zyglpt/resourceCategoryList');
		//组合body请求数据
		$bodyParams = array(
			'title' => $title,
			'status' => $status,
			'startTime' => $startTime,
			'endTime' => $endTime,
			'pageSize' => $pageSize,
			'pageNumber' => $pageNumber,
			'token' => $token
		);
		//组合query请求数据
		$queryParams = array(
			'appID' => self::__getCzyInterfaceAppId(),
			'ts' => $time,
			'sign' => self::__getEncryptSign(self::__getSign($time))
		);
		//请求ICE接口
		return self::__result(self::curlPostJson($url, $queryParams, $bodyParams));
	}

	/**
	 * 获取资源列表
	 * @param $token
	 * @param $category
	 * @param $categoryId
	 * @param $platform
	 * @param $name
	 * @param $status
	 * @param $grantType
	 * @param $callType
	 * @param $pageSize
	 * @param $pageNumber
	 * @return array
	 */
	static function zyglptResourceGetList($token, $category, $categoryId, $platform, $name, $status, $grantType, $callType, $pageSize, $pageNumber)
	{
		$time = time();
		$url = self::__getCzyInterfaceApiUrl('v1/zyglpt/resourceGetList');
		//组合body请求数据
		$bodyParams = array(
			'token' => $token,
			'category' => $category,
			'categoryId' => $categoryId,
			'platform' => $platform,
			'name' => $name,
			'status' => $status,
			'grantType' => $grantType,
			'callType' => $callType,
			'pageSize' => $pageSize,
			'pageNumber' => $pageNumber,
		);
		//组合query请求数据
		$queryParams = array(
			'appID' => self::__getCzyInterfaceAppId(),
			'ts' => $time,
			'sign' => self::__getEncryptSign(self::__getSign($time))
		);
		//请求ICE接口
		return self::__result(self::curlPostJson($url, $queryParams, $bodyParams));
	}

	/**
	 * 获取活动的资源明细
	 * @param $token
	 * @param $orderNumber
	 * @return array
	 */
	static function zyglptActivityResourceDetail($token, $orderNumber, $search, $categoryId, $pageSize, $pageNumber)
	{
		$time = time();
		$url = self::__getCzyInterfaceApiUrl('v1/zyglpt/activityResourceDetail');
		//组合body请求数据
		$bodyParams = array(
			'orderNumber' => $orderNumber,
			'search' => $search,
			'categoryId' => $categoryId,
			'pageSize' => $pageSize,
			'pageNumber' => $pageNumber,
			'appId' => self::__getCzyInterfaceAppId(),
			'token' => $token
		);
		//组合query请求数据
		$queryParams = array(
			'appID' => self::__getCzyInterfaceAppId(),
			'ts' => $time,
			'sign' => self::__getEncryptSign(self::__getSign($time))
		);
		//请求ICE接口
		return self::__result(self::curlPostJson($url, $queryParams, $bodyParams));
	}

	/**
	 * 资源申请
	 * @param $appName
	 * @param $userName
	 * @param $orderNumber
	 * @param $actName
	 * @param $callbackUrl
	 * @param $token
	 * @return array
	 */
	static function zyglptApplyActivityResource($appName, $userName, $orderNumber, $actName, $callbackUrl, $resource, $token)
	{
		$time = time();
		$url = self::__getCzyInterfaceApiUrl('v1/zyglpt/applyActivityResource');
		//组合body请求数据
		$bodyParams = array(
			'appId' => self::__getCzyInterfaceAppId(),
			'appName' => $appName,
			'username' => $userName,
			'orderNumber' => $orderNumber,
			'activity' => $actName,
			'callbackUrl' => $callbackUrl,
			'resource' => $resource,
			'token' => $token
		);
		//组合query请求数据
		$queryParams = array(
			'appID' => self::__getCzyInterfaceAppId(),
			'ts' => $time,
			'sign' => self::__getEncryptSign(self::__getSign($time))
		);
		//请求ICE接口
		return self::__result(self::curlPostJson($url, $queryParams, $bodyParams));
	}

	/**
	 * 消费活动资源
	 * @param $token
	 * @param $appId
	 * @param $orderNumber
	 * @param $activity
	 * @param $resourceId
	 * @param $number
	 * @param $type
	 * @param $mobile
	 * @param $username
	 * @return array
	 */
	static function zyglptUseActivityResource($token, $orderNumber, $activity, $resourceId, $number, $mobile, $username)
	{
		$time = time();
		$url = self::__getCzyInterfaceApiUrl('v1/zyglpt/useActivityResource');
		//组合body请求数据
		$bodyParams = array(
			'token' => $token,
			'appId' => self::__getCzyInterfaceAppId(),
			'orderNumber' => $orderNumber,
			'activity' => $activity,
			'resourceId' => $resourceId,
			'number' => $number,
			'mobile' => $mobile,
			'username' => $username,
		);
		//组合query请求数据
		$queryParams = array(
			'appID' => self::__getCzyInterfaceAppId(),
			'ts' => $time,
			'sign' => self::__getEncryptSign(self::__getSign($time))
		);
		//请求ICE接口
		return self::__result(self::curlPostJson($url, $queryParams, $bodyParams));
	}

	/**
	 * 获取消费记录明细
	 * @param $token
	 * @param $orderNumber
	 * @return array
	 */
	static function zyglptActivityEndFreeResource($token, $orderNumber)
	{
		$time = time();
		$url = self::__getCzyInterfaceApiUrl('v1/zyglpt/activityEndFreeResource');
		//组合body请求数据
		$bodyParams = array(
			'token' => $token,
			'appId' => self::__getCzyInterfaceAppId(),
			'orderNumber' => $orderNumber,
		);
		//组合query请求数据
		$queryParams = array(
			'appID' => self::__getCzyInterfaceAppId(),
			'ts' => $time,
			'sign' => self::__getEncryptSign(self::__getSign($time))
		);
		//请求ICE接口
		return self::__result(self::curlPostJson($url, $queryParams, $bodyParams));
	}


//	/**
//	 * 抽奖测试
//	 */
//	/**
//	 * 申请抽奖资格
//	 * @param $token
//	 * @param $orderNumber
//	 * @return array
//	 */
//	static function hdglptLotteryQualificationsApply($token, $number, $mobile)
//	{
//		$time = time();
//		$url = self::__getCzyInterfaceApiUrl('v1/hdglpt/lotteryQualificationsApply');
//		//组合body请求数据
//		$bodyParams = array(
//			'token' => $token,
//			'number' => $number,
//			'mobile' => $mobile,
//		);
//		//组合query请求数据
//		$queryParams = array(
//			'appID' => self::__getCzyInterfaceAppId(),
//			'ts' => $time,
//			'sign' => self::__getEncryptSign(self::__getSign($time))
//		);
//		//请求ICE接口
//		return self::__result(self::curlPostJson($url, $queryParams, $bodyParams));
//	}
//	/**
//	 * 抽奖
//	 * @param $token
//	 * @param $orderNumber
//	 * @return array
//	 */
//	static function hdglptGetLotteryResult($token, $number, $mobile)
//	{
//		$time = time();
//		$url = self::__getCzyInterfaceApiUrl('v1/hdglpt/getLotteryResult');
//		//组合body请求数据
//		$bodyParams = array(
//			'token' => $token,
//			'number' => $number,
//			'mobile' => $mobile,
//		);
//		//组合query请求数据
//		$queryParams = array(
//			'appID' => self::__getCzyInterfaceAppId(),
//			'ts' => $time,
//			'sign' => self::__getEncryptSign(self::__getSign($time))
//		);
//		//请求ICE接口
//		return self::__result(self::curlPostJson($url, $queryParams, $bodyParams));
//	}
//	/**
//	 * 获取消费记录明细
//	 * @param $token
//	 * @param $orderNumber
//	 * @return array
//	 */
//	static function hdglptQualificationsApplyAndGetLotteryResult($token, $number, $mobile)
//	{
//		$time = time();
//		$url = self::__getCzyInterfaceApiUrl('v1/hdglpt/qualificationsApplyAndGetLotteryResult');
//		//组合body请求数据
//		$bodyParams = array(
//			'token' => $token,
//			'number' => $number,
//			'mobile' => $mobile,
//		);
//		//组合query请求数据
//		$queryParams = array(
//			'appID' => self::__getCzyInterfaceAppId(),
//			'ts' => $time,
//			'sign' => self::__getEncryptSign(self::__getSign($time))
//		);
//		//请求ICE接口
//		return self::__result(self::curlPostJson($url, $queryParams, $bodyParams));
//	}


}