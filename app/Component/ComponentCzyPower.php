<?php

namespace App\Component;


/**
 * 彩之云-权限微服务 权限
 * User: Administrator
 */
class ComponentCzyPower extends ComponentCzy
{
	/**
	 * 创建应用权限
	 * @param $type
	 * @param $parentUuid
	 * @param $name
	 * @param $model
	 * @param $token
	 * @return array
	 */
	static function qxwfwApplicationPrivilegeCreate($type, $parentUuid, $name, $model, $token)
	{
		$url = self::__getCzyInterfaceApiUrl('v1/qxwfw/application/privilege/create');
		//组合body请求数据
		$bodyParams = array(
			'type' => $type,
			'name' => $name,
			'model' => $model,
			'access_token' => $token,
			'parent_uuid' => $parentUuid,
			'application_uuid' => self::__getCzyInterfacePowerUuid(),
		);

		//组合query请求数据
		$queryParams = array(
			'appID' => self::__getCzyInterfaceAppId(),
			'ts' => time(),
			'sign' => self::__getEncryptSign(self::__getSign(time()))
		);
		//请求ICE接口
		return self::__result(self::curlPostJson($url, $queryParams, $bodyParams));
	}
	/**
	 * 编辑应用权限
	 * @param $type		运营/商户
	 * @param $name
	 * @param $model
	 * @param $token
	 * @return mixed
	 */
	static function qxwfwApplicationPrivilegeEdit($privilegeUuid, $name, $model, $parentUuid, $token)
	{
		$url = self::__getCzyInterfaceApiUrl('v1/qxwfw/application/privilege/edit');
		//组合body请求数据
		$bodyParams = array(
			'privilege_uuid' => $privilegeUuid,
			'parent_uudi' => $parentUuid,
			'name' => $name,
			'model' => $model,
			'access_token' => $token,
		);

		//组合query请求数据
		$queryParams = array(
			'appID' => self::__getCzyInterfaceAppId(),
			'ts' => time(),
			'sign' => self::__getEncryptSign(self::__getSign(time()))
		);
		//请求ICE接口
		return self::__result(self::curlPostJson($url, $queryParams, $bodyParams));
	}

	/**
	 * 应用权限列表
	 * @param $list_type		类型 平台/商户
	 * @param $list_type       返回格式 子结构/列表
	 * @param $token
	 * @param array $wheres
	 * @return array
	 */
	static function qxwfwApplicationPrivilegeList($type, $list_type, $token, $wheres=[], $page=1, $pageSize=10)
	{
		$url = self::__getCzyInterfaceApiUrl('v1/qxwfw/application/privilege/list');
		//组合body请求数据
		$bodyParams = array(
			'page' => $page,
			'page_size' => $pageSize,
			'list_type' => $list_type,
			'access_token' => $token,
			'application_uuid' => self::__getCzyInterfacePowerUuid(),
			'appID' => self::__getCzyInterfaceAppId(),
			'ts' => time(),
			'sign' => self::__getEncryptSign(self::__getSign(time()))
		);
		if ($type) $bodyParams['type'] = $type;
		$bodyParams = array_merge($bodyParams,$wheres);
		//请求ICE接口
		return self::__result(self::curlGet($url, $bodyParams));
	}
	/**
	 * 应用用户权限
	 * @param $userUuid
	 * @param $token
	 * @return array
	 */
	static function qxwfwApplicationUserPrivilegeList($userUuid, $token)
	{
		$url = self::__getCzyInterfaceApiUrl('v1/qxwfw/application/user/privilege/list');
		//组合body请求数据
		$bodyParams = array(
			'application_uuid' => self::__getCzyInterfacePowerUuid(),
			'user_uuid' => $userUuid,
			'access_token' => $token,
			'appID' => self::__getCzyInterfaceAppId(),
			'ts' => time(),
			'sign' => self::__getEncryptSign(self::__getSign(time()))
		);
		//请求ICE接口
		return self::__result(self::curlGet($url, $bodyParams));
	}

	/**
	 * 应用权限删除
	 * @param $privilegeUuid
	 * @param $token
	 * @return array
	 */
	static function qxwfwapplicationprivilegedel($privilegeUuid, $token)
	{
		$url = self::__getCzyInterfaceApiUrl('v1/qxwfw/application/privilege/del');
		//组合body请求数据
		$bodyParams = array(
			'application_privilege_uuid' => $privilegeUuid,
			'access_token' => $token,
		);
		//组合query请求数据
		$queryParams = array(
			'appID' => self::__getCzyInterfaceAppId(),
			'ts' => time(),
			'sign' => self::__getEncryptSign(self::__getSign(time()))
		);
		//请求ICE接口
		return self::__result(self::curlPostJson($url, $queryParams, $bodyParams));
	}
}