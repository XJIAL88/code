<?php

namespace App\Component;


/**
 * 彩之云-员工微服务
 * User: Administrator
 * Date: 2016/7/13
 * Time: 16:44
 */
class ComponentCzyEmployee extends ComponentCzy
{

	/**
	 * 匹配员工账号密码
	 * @param string $username
	 * @param string $password
	 * @return array
	 */
	static function employeeAccountLogin($username = "", $password = "")
	{
		//获取基本配置
		$time = time();
		$url = self::__getCzyInterfaceApiUrl('v1/employee/accountLogin');
		//组合body请求数据
		$bodyParams = array(
			'username' => $username,
			'password' => $password,
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
	 * 查询员工账号
	 * @param string $accountUuid
	 * @param string $username
	 * @param string $token
	 * @return array
	 */
	static function employeeAccount($accountUuid = "", $username = "", $token = "")
	{
		//获取基本配置
		$time = time();
		$url = self::__getCzyInterfaceApiUrl('v1/employee/account');
		//组合请求数据
		$params = array(
			'account_uuid' => $accountUuid,
			'username' => $username,
			'token' => $token,
			'appID' => self::__getCzyInterfaceAppId(),
			'ts' => $time,
			'sign' => self::__getEncryptSign(self::__getSign($time))
		);
		//请求ICE接口
		return self::__result(self::curlGet($url, $params));
	}

	/**
	 * 手机号码查询员工账号【创建商户时调用】
	 * @param string $mobile
	 * @param string $token
	 * @return array
	 */
	static function customerGetInfo($mobile = "", $token = "")
	{
		//获取基本配置
		$time = time();
		$url = self::__getCzyInterfaceApiUrl('v1/czyuser/czy/userInfoByMobile');
       // $url = "https://openapi.colourlife.com/v1/czyuser/czy/userInfoByMobile";
		//组合请求数据
		$params = array(
			'sign' => self::__getEncryptSign(self::__getSign($time)),
			'ts' => $time,
			'appID' => self::__getCzyInterfaceAppId(),
			'mobile' => $mobile,
			'access_token' => $token,
		);
		//请求ICE接口
		return self::__result(self::curlGet($url, $params));
	}


}