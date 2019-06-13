<?php

namespace App\Component;

use App\Bases\BaseService;

/**
 * 彩之云-授权
 * User: Administrator
 * Date: 2016/7/13
 * Time: 16:44
 */
class ComponentCzyAuthorize extends ComponentCzy
{
	/**
	 * 获取调用方accessToken
	 * @return array
	 */
	static function authmsAuthApp()
	{
		//
		$time = time();
		$url = self::__getCzyInterfaceApiUrl('v1/authms/auth/app');
		//
		$datas = array(
			'corp_uuid' => self::__getCzyInterfaceCorpUuid(),
			'app_uuid' => self::__getCzyInterfaceAppId(),
			'timestamp' => $time
		);
		$datas['signature'] = self::__getEncryptSignature(self::__getSignature($time));
		//
		$params = array(
			'appID' => self::__getCzyInterfaceAppId(),
			'ts' => $time,
			'sign' => self::__getEncryptSign(self::__getSign($time))
		);
		return self::__result(self::curlPost($url, $params, $datas));
	}

	/**
	 * 获取彩之云授权URL（Oauth2.0）
	 * @param $redirectUri
	 * @param string $scope
	 * @param string $state
	 * @return string
	 */
	static function oauth2Authorize($redirectUri, $scope = '[]', $state = '')
	{
		//
		$url = self::__getCzyOauthApiUrl('oauth2/authorize');
		//
		$apiParams = array(
			'application_id=' . self::__getCzyInterfaceAppId(),
			'redirect_uri=' . $redirectUri,
			'response_type=' . 'code',
			'scope=' . $scope,
			'state=' . $state
		);
		return $url . '?' . implode('&', $apiParams);
	}

	/**
	 * 获取AccessToken
	 * @param $code
	 * @return mixed
	 */
	static function oauthAccessToken($code)
	{
		//
		$url = self::__getCzyOauthApiUrl('oauth/access_token');
		//
		$params = array(
			'application_id' => self::__getCzyInterfaceAppId(),
			'secret' => self::__getCzyInterfaceSecret(),
			'code' => $code,
			'grant_type' => 'authorization_code'
		);
		return self::__result(self::curlGet($url, $params));
	}

	/**
	 * 获取彩之云用户信息（Oauth2.0）
	 * @param $accessToken
	 * @return array
	 */
	static function oauthUserInfo($accessToken)
	{
		//
		$url = self::__getCzyOauthApiUrl('oauth/user/info');
		//
		$params = array(
			'access_token' => $accessToken
		);
		return self::__result(self::curlGet($url, $params));
	}

	////////////////////////////////////         后端授权登陆          //////////////////////////////////////////

	/**
	 * web端Oauth2.0授权(运营平台)
	 * @param $redirectUri
	 * @param string $state
	 * @param string $accountType
	 * @return string
	 */
	static function oauth2AuthorizeWithWebByPlatform($redirectUri, $state = '', $accountType = "")
	{
		//获取ICE配置
		$url = self::__getCzyOauthAdminApiUrl('oauth/authorize');
		//组合请求
		$apiParams = array(
			'response_type=' . 'code',
			'scope=' . 'read%20write',
			'client_id=' . self::__getCzyInterfaceAppId(),
			'redirect_uri=' . $redirectUri,
			'state=' . $state,
			'accountType=' . $accountType
		);
		return $url . '?' . implode('&', $apiParams);
	}

	/**
	 * 退出（Oauth2.0）
	 * @return array
	 */
	static function oauth2Logout()
	{
		//
		$url = self::__getCzyOauthAdminApiUrl('oauth/logout');
		//
		$clientId = self::__getCzyInterfaceAppId();
		$params = array(
			'client_id' => $clientId,
			'response_type' => 'code'
		);
		return self::__logoutResult(self::curlGet($url, $params));
	}

	/**
	 * 退出（Oauth2.0）
	 * @return array
	 */
	static function oauth2LogoutUrl()
	{
		//
		$url = self::__getCzyOauthAdminApiUrl('oauth/logout');
		//
		$clientId = self::__getCzyInterfaceAppId();
		$params = array(
			'client_id=' . $clientId,
			'response_type=' . 'code'
		);
		return BaseService::returnSuccess($url . '?' . implode('&', $params));
	}

	/**
	 * 获取access_token（运营平台）
	 * @param $code
	 * @param $redirectUri
	 * @param $state
	 * @return array
	 */
	static function oauthAccessTokenByPlatform($code, $redirectUri, $state)
	{
		//
		$url = self::__getCzyOauthAdminApiUrl('oauth/token');
		//
		$params = array(
			'client_id' => self::__getCzyInterfaceAppId(),
			'client_secret' => self::__getCzyInterfaceSecret(),
			'grant_type' => 'authorization_code',
			'code' => $code,
			'redirect_uri' => $redirectUri,
			'state' => $state
		);
		return self::__oauthResult(self::curlPostOAuth($url, $params, $params));
	}

	/**
	 * web端Oauth2.0（商户平台）
	 * @param $redirectUri
	 * @param string $state
	 * @return string
	 */
	static function oauth2AuthorizeWithWebByTrader($redirectUri, $state = '')
	{
		//获取ICE配置
		$url = self::__getCzyOauthApiUrl('oauth2/web/authorize');
		//组合请求
		$apiParams = array(
			'application_id=' . self::__getCzyInterfaceAppId(),
			'redirect_uri=' . $redirectUri,
			'response_type=' . 'code',
			'scope=' . 'get_userinfo',
			'state=' . $state
		);
		return $url . '?' . implode('&', $apiParams);
	}

	/**
	 * 获取服务accessToken
	 * @return array
	 */
	static function authmsAuthService()
	{
		//
		$time = time();
		$url = self::__getCzyInterfaceApiUrl('v1/authms/auth/service');
		//
		$datas = array(
			'service_uuid' => self::__getCzyInterfaceServiceUuid(),
			'timestamp' => $time
		);
		$datas['signature'] = self::__getEncryptSignature(self::__getServiceSignature($time));
		//
		$params = array(
			'appID' => self::__getCzyInterfaceAppId(),
			'ts' => $time,
			'sign' => self::__getEncryptSign(self::__getSign($time))
		);
		return self::__result(self::curlPost($url, $params, $datas));
	}

	/**
	 * 获取调用方权限列表
	 * @param string $serviceToken
	 * @param string $appToken
	 * @return array
	 */
	static function authmsAppPrivilege($serviceToken = '', $appToken = '')
	{
		//
		$time = time();
		$url = self::__getCzyInterfaceApiUrl('v1/authms/app/privilege');
		//
		$params = array(
			'appID' => self::__getCzyInterfaceAppId(),
			'ts' => $time,
			'sign' => self::__getEncryptSign(self::__getSign($time)),
			'service_token' => $serviceToken,
			'app_token' => $appToken
		);
		return self::__result(self::curlGet($url, $params));
	}


}