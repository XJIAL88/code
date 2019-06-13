<?php

namespace App\Component;


/**
 * 彩之云-权限微服务 角色
 * User: Administrator
 */
class ComponentCzyRole extends ComponentCzy
{
	/**
	 * 创建角色
	 * @param $name
	 * @param $type
	 * @param $extend
	 * @param $token
	 * @return array
	 */
	static function qxwfwRoleCreate($name, $type, $extend, $token)
	{
		$url = self::__getCzyInterfaceApiUrl('v1/qxwfw/role/create');
		$time = time();
		//组合body请求数据
		$bodyParams = array(
			'extend' => $extend,
			'name' => $name,
			'type' => $type,
			'access_token' => $token,
			'application_uuid' => self::__getCzyInterfacePowerUuid(),
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
	 * 角色详情
	 * @param $name
	 * @param $token
	 * @return array
	 */
	static function qxwfwRoleDetail($name, $token)
	{
		$url = self::__getCzyInterfaceApiUrl('v1/qxwfw/role/detail');
		$time = time();
		//组合body请求数据
		$bodyParams = array(
			'role_name' => urlencode($name),
			'access_token' => $token,
			'application_uuid' => self::__getCzyInterfacePowerUuid(),
			'appID' => self::__getCzyInterfaceAppId(),
			'ts' => $time,
			'sign' => self::__getEncryptSign(self::__getSign($time))
		);

		//请求ICE接口
		return self::__result(self::curlGet($url, $bodyParams));
	}


	/**
	 * 编辑角色
	 * @param $roleUuid
	 * @param $name
	 * @param $token
	 * @return array
	 */
	static function qxwfwRoleEdit($roleUuid, $name, $type, $token)
	{
		$url = self::__getCzyInterfaceApiUrl('v1/qxwfw/role/edit');
		$time = time();
		//组合body请求数据
		$bodyParams = array(
			'type' => $type,
			'role_uuid' => $roleUuid,
			'name' => $name,
			'access_token' => $token,
			'application_uuid' => self::__getCzyInterfacePowerUuid(),
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
	 * 角色列表
	 * @param $token
	 * @param $wheres
	 * @param int $pageNumber
	 * @param int $pageSize
	 * @return array
	 */
	static function qxwfwApplicationRoleList($token, $wheres, $pageNumber = 1, $pageSize = 10)
	{
		$url = self::__getCzyInterfaceApiUrl('v1/qxwfw/application/role/list');
		$time = time();
		//组合body请求数据
		$bodyParams = array(
			'page' => $pageNumber,
			'page_size' => $pageSize,
			'access_token' => $token,
			'application_uuid' => self::__getCzyInterfacePowerUuid(),
			'appID' => self::__getCzyInterfaceAppId(),
			'ts' => $time,
			'sign' => self::__getEncryptSign(self::__getSign($time))
		);
		$bodyParams = array_merge($bodyParams, $wheres);
		//请求ICE接口
		return self::__result(self::curlGet($url, $bodyParams));
	}

	/**
	 * 角色权限关联
	 * @param $privilege_uuid
	 * @param $role_uuid
	 * @param $token
	 * @return array
	 */
	static function qxwfwRolePrivilegeCreate($applicationPrivilegeUuid, $role_uuid, $token)
	{
		$url = self::__getCzyInterfaceApiUrl('v1/qxwfw/role/privilege/create');
		$time = time();
		//组合body请求数据
		$bodyParams = array(
			'application_privilege_uuid' => $applicationPrivilegeUuid,
			'role_uuid' => $role_uuid,
			'access_token' => $token,
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
	 * 角色权限列表
	 * @param $type
	 * @param $roleUuid
	 * @param $token
	 * @return array
	 */
	static function qxwfwApplicationRolePrivilegeList($type, $roleUuid, $token)
	{
		$url = self::__getCzyInterfaceApiUrl('v1/qxwfw/application/role/privilege/list');
		$time = time();
		//组合body请求数据
		$bodyParams = array(
			'type' => $type,
			'application_uuid' => self::__getCzyInterfacePowerUuid(),
			'role_uuid' => $roleUuid,
			'access_token' => $token,
			'appID' => self::__getCzyInterfaceAppId(),
			'ts' => $time,
			'sign' => self::__getEncryptSign(self::__getSign($time))
		);
		//请求ICE接口
		return self::__result(self::curlGet($url, $bodyParams));
	}

	/**
	 * 删除应用角色
	 * @param $role_uuid
	 * @param $token
	 * @return array
	 */
	static function qxwfwRoleDel($roleUuid, $token)
	{
		$url = self::__getCzyInterfaceApiUrl('v1/qxwfw/role/del');
		$time = time();
		//组合body请求数据
		$bodyParams = array(
			'role_uuid' => $roleUuid,
			'access_token' => $token,
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
	 * 应用角色权限删除
	 * @param $role_uuid
	 * @param $token
	 * @return array
	 */
	static function qxwfwRolePrivilegeDel($privilegeUuid, $roleUuid, $token)
	{
		$url = self::__getCzyInterfaceApiUrl('v1/qxwfw/role/privilege/del');
		$time = time();
		//组合body请求数据
		$bodyParams = array(
			'role_uuid' => $roleUuid,
			'application_privilege_uuid' => $privilegeUuid,
			'access_token' => $token,
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
	 * 应用角色用户关联
	 * @param $role_uuid
	 * @param $user_uuid
	 * @param $token
	 * @return array
	 */
	static function qxwfwRoleUserCreate($roleUuid, $username, $userUuid, $token)
	{
		$url = self::__getCzyInterfaceApiUrl('v1/qxwfw/role/user/create');
		$time = time();
		//组合body请求数据
		$bodyParams = array(
			'role_uuid' => $roleUuid,
			'user_uuid' => $userUuid,
			'user_name' => $username,
			'application_uuid' => self::__getCzyInterfacePowerUuid(),
			'access_token' => $token,
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
	 * 删除角色用户关联
	 * @param $role_uuid
	 * @param $user_uuid
	 * @param $token
	 * @return array
	 */
	static function qxwfwRoleUserDel($roleUuid, $userUuid, $token)
	{
		$url = self::__getCzyInterfaceApiUrl('v1/qxwfw/role/user/del');
		$time = time();
		//组合body请求数据
		$bodyParams = array(
			'role_uuid' => $roleUuid,
			'user_uuid' => $userUuid,
			'access_token' => $token,
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
	 * 通过用户uuid应用uuid查询角色
	 * @param $user_uuid
	 * @param $token
	 * @return array
	 */
	static function qxwfwRoleUserDetail($userUuid, $token)
	{
		$url = self::__getCzyInterfaceApiUrl('v1/qxwfw/role/user/detail');
		$time = time();
		//组合body请求数据
		$bodyParams = array(
			'user_uuid' => $userUuid,
			'application_uuid' => self::__getCzyInterfacePowerUuid(),
			'access_token' => $token,
			'appID' => self::__getCzyInterfaceAppId(),
			'ts' => $time,
			'sign' => self::__getEncryptSign(self::__getSign($time))
		);
		//请求ICE接口
		return self::__result(self::curlGet($url, $bodyParams));
	}

	/**
	 * 角色用户列表接口
	 * @param $role_uuid
	 * @param $token
	 * @return array
	 */
	static function qxwfwRoleUserList($roleUuid, $token)
	{
		$url = self::__getCzyInterfaceApiUrl('v1/qxwfw/role/user/list');
		$time = time();
		//组合body请求数据
		$bodyParams = array(
			'role_uuid' => $roleUuid,
			'access_token' => $token,
			'appID' => self::__getCzyInterfaceAppId(),
			'ts' => $time,
			'sign' => self::__getEncryptSign(self::__getSign($time))
		);
		//请求ICE接口
		return self::__result(self::curlGet($url, $bodyParams));
	}

	/**
	 * 创建角色
	 * @param $name
	 * @param $type
	 * @param $extend
	 * @param $token
	 * @return array
	 */
	static function test($appId, $token)
	{
//		$url = "http://resource.zenggp.com/api/applyActivityResource";
//		$url = "http://czy-resource.test.szzhihuiyun.com/api/applyActivityResource";
		$url = "https://openapi-test.colourlife.com/v1/zyglpt/applyActivityResource";
		$time = time();
		//组合body请求数据
		$bodyParams = array(
			'callbackUrl' => 'http://www.baidu.com',
			'activity' => '曾广鹏测试',
			'orderNumber' => 'zenggp001',
			'appName' => '曾广鹏测试',
			'appId' => $appId,
			'username' => '13316464480',
			'token' => $token,
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


}