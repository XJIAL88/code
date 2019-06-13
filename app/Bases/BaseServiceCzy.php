<?php

namespace App\Bases;

use App\Component\ComponentCzyAuthorize;
use Monolog\Logger;

/**
 * 业务基类-彩之云
 * User: Administrator
 * Date: 2016/7/28
 * Time: 16:06
 */
class BaseServiceCzy extends BaseService
{
	static $errorArray = array(
		'czy1001' => '微服务AppToken获取失败',
		'czy1002' => '微服务Privilege获取失败',
		'czy1003' => '微服务ServiceToken获取失败',
	);

	/**
	 * 获取AppToken
	 * @return array
	 */
	static function getAppToken()
	{
		try {
			//
			$cacheKey = self::getCacheKey(__CLASS__ . '.' . __FUNCTION__);
			// 获取缓存
			if (($cacheResult = self::getCache($cacheKey))) {
				return self::returnSuccess($cacheResult);
			}
			// 获取AccessToken
			$appToken = ComponentCzyAuthorize::authmsAuthApp();
			if ($appToken['code']) {
				self::log('czy', __CLASS__ . '->' . __FUNCTION__, self::$errorArray['czy1001'], Logger::ERROR);
				return $appToken;
			}
			// 设置缓存
			self::setCache($cacheKey, $appToken['data']['accessToken'], (intval($appToken['data']['expireTime'] / 1000) - time()) / 60);
			//
			return self::returnSuccess($appToken['data']['accessToken']);
		} catch (\Exception $e) {
			self::log('czy', __CLASS__ . '->' . __FUNCTION__, $e->getMessage(), Logger::ERROR);
			return self::returnError('czy1001', self::$errorArray['czy1001']);
		}
	}

	/**
	 * 获取ServiceToken
	 * @return array
	 */
	static function getServiceToken()
	{
		try {
			//
			$cacheKey = self::getCacheKey(__CLASS__ . '.' . __FUNCTION__);
			// 获取缓存
			if (($cacheResult = self::getCache($cacheKey))) {
				return self::returnSuccess($cacheResult);
			}
			// 获取AccessToken
			$serviceToken = ComponentCzyAuthorize::authmsAuthService();
			if ($serviceToken['code']) {
				self::log('czy', __CLASS__ . '->' . __FUNCTION__, self::$errorArray['czy1003'], Logger::ERROR);
				return $serviceToken;
			}
			// 设置缓存
			self::setCache($cacheKey, $serviceToken['data']['accessToken'], (intval($serviceToken['data']['expireTime'] / 1000) - time()) / 60);
			//
			return self::returnSuccess($serviceToken['data']['accessToken']);
		} catch (\Exception $e) {
			self::log('czy', __CLASS__ . '->' . __FUNCTION__, $e->getMessage(), Logger::ERROR);
			return self::returnError('czy1003', self::$errorArray['czy1003']);
		}
	}

	/**
	 * 获取调用方权限列表
	 * @param $token
	 * @return array
	 */
	static function getPrivilege($token)
	{
		try {
			//
			$cacheKey = self::getCacheKey(__CLASS__ . '.' . __FUNCTION__, array($token));
			// 获取缓存
			if (($cacheResult = self::getCache($cacheKey))) {
				return self::returnSuccess($cacheResult);
			}
			// 获取ServiceToken
			$serviceToken = self::getServiceToken();
			if ($serviceToken['code']) {
				self::log('czy', __CLASS__ . '->' . __FUNCTION__, self::$errorArray['czy1002'], Logger::ERROR);
				return $serviceToken;
			}
			// 获取调用方权限列表
			$privilege = ComponentCzyAuthorize::authmsAppPrivilege($serviceToken['data'], $token);
			if ($privilege['code']) {
				self::log('czy', __CLASS__ . '->' . __FUNCTION__, self::$errorArray['czy1002'], Logger::ERROR);
				return $privilege;
			}
			// 设置缓存
			self::setCache($cacheKey, $privilege['data']['privileges'], 30);
			//
			return self::returnSuccess($privilege['data']['privileges']);
		} catch (\Exception $e) {
			self::log('czy', __CLASS__ . '->' . __FUNCTION__, $e->getMessage(), Logger::ERROR);
			return self::returnError('czy1002', self::$errorArray['czy1002']);
		}
	}
}