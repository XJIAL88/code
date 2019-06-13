<?php

namespace App\Bases;

use App\Component\ComponentCzyEmployee;
use App\Component\ComponentCzyRole;
use App\Component\ComponentRandom;
use App\Manages\ManageAdmin;
use App\Manages\ManageRole;
use App\Services\ServiceAdminRole;
use Monolog\Logger;

/**
 * 业务基类-后台业务
 * Class BaseServiceAdmin
 * @package App\Services
 */
class BaseServiceAdmin extends BaseService
{
	static $errorArray = array(
		1101 => '账户不存在',
		1102 => '密码错误',
		1103 => '登录失败',
		1104 => '账户失效',

		2001 => '彩之云账户信息不存在',
		2002 => '彩之云账户本地不存在',
		2003 => '更新用户token失败',
		2004 => '账户身份非法',
		2005 => '账户被禁用,请联系管理员',
		2006 => '账户权限获取失败',
		2007 => '角色不存在',
		2008 => '账号为管理平台账号,不能登录申请平台',
		2009 => '账号为申请平台账号,不能登录管理平台',
	);

	/**
	 * 信息日志
	 * @param $title
	 * @param $content
	 * @param string $file
	 */
	static function logInfo($title, $content, $file = 'lumen')
	{
		self::log('admin', $title, $content, Logger::INFO, $file);
	}

	/**
	 * 错误日志
	 * @param $title
	 * @param $content
	 * @param string $file
	 */
	static function logError($title, $content, $file = 'lumen')
	{
		self::log('admin', $title, $content, Logger::ERROR, $file);
	}

	/**
	 * 是否登录
	 * @param $adminId
	 * @param $adminToken
	 * @return bool|mixed
	 */
	static function isLogin($adminId, $adminToken)
	{
		// 获取账户信息，并判断身份是否合法
		$admin = self::arObject2Array(ManageAdmin::get(self::getConnection(true), $adminId));
		// 身份不匹配
		if (empty($admin) || intval($admin['status']) !== 1) {
			return -3;
		}
		// 判断token是否存在，不存在则是被T
		if ($admin['token'] !== $adminToken) {
			return -1;
		}
		// 过期
//		if (intval($admin['tokenOverAt']) <= time()) {
//			return -2;
//		}
		// 获取角色信息
//		$role = ServiceAdminRole::roleGet($admin['roleUuid']);
//		if ($role['code'] !== 0 || empty($role['data'])) {
//			return -3;
//		}

		// 更新用户的超时时间
		ManageAdmin::update(self::getConnection(), $adminId, array(
			'token_over_at' => time() + intval($_ENV['PROJECT_adminTokenOverTime'])
		));
//		$resultRole = $role['data'];
		$admin['roleTitle'] = '';
		$admin['roleSuper'] = '';
		$admin['rolePower'] = '';
		return $admin;
	}

	/**
	 * 创建Token
	 * @param $id
	 * @return string
	 */
	static function __createToken($id)
	{
		return md5($id . ComponentRandom::genRandomStr(32));
	}

	/**
	 * 创建密匙
	 * @return string
	 */
	static function __createSecret()
	{
		return ComponentRandom::genRandomStr(8);
	}

	/**
	 * 密码加密
	 * @param $password
	 * @param $secret
	 * @return string
	 */
	static function __encryptPassword($password, $secret)
	{
		return md5('wfwD#$#@dd154' . $password . $secret . 'jhd&&*GHFsdfhg');
	}


	///////////////////////////////////////            授权登陆              /////////////////////////////////////////

	/**
	 * 授权返回本地登陆更新token【运营平台】
	 * @param string $czyUserName
	 * @return array
	 */
	static function loginOauthByPlatform($czyUserName = "", $type = 1)
	{
		//获取彩之云用户信息
		if (empty($czyUserName)) {
			return self::returnError(2001, self::$errorArray[2001]);
		}
		//获取是否存在该用户名
		$admin = self::arObject2Array(ManageAdmin::getWith(self::getConnection(), 'username', $czyUserName));
		if (empty($admin)) {
			return self::returnError(2002, self::$errorArray[2002]);
		}

		//判断账户状态
		if (intval($admin["status"]) !== 1) {
			return self::returnError(2005, self::$errorArray[2005]);
		}

		if ($type == 1){
			//管理平台
			if (!empty($admin['appid'])){
				return self::returnError(2009, self::$errorArray[2009]);
			}
		}elseif($type == 2){
			//申请平台
			if (empty($admin['appid'])){
				return self::returnError(2008, self::$errorArray[2008]);
			}
		}

		//更新该用户的token跟token时间
		$adminId = $admin['id'];
		$adminToken = self::__createToken($adminId);
		$adminTokenOverTime = time() + intval($_ENV['PROJECT_adminTokenOverTime']);
		$result = ManageAdmin::update(self::getConnection(), $adminId, array(
			'token' => $adminToken,
			'token_over_at' => intval($adminTokenOverTime)
		));
		if (empty($result)) {
			return self::returnError(2003, self::$errorArray[2003]);
		}
		//返回
		return self::returnSuccess(array(
			'id' => $adminId,
			'token' => $adminToken,
			'name' => $admin['username'],
		));
	}

	/**
	 * 用户授权返回本地登陆更新token【商户平台】
	 * @param string $mobile
	 * @param string $nickname
	 * @return array
	 */
	static function loginOauthByTrader($mobile = "", $nickname = "")
	{
		//获取彩之云用户信息
		if (empty($mobile)) {
			return self::returnError(2001, self::$errorArray[2001]);
		}
		//获取是否存在该用户名
		$admin = self::arObject2Array(ManageAdmin::getWith(self::getConnection(), 'username', $mobile));
		if (empty($admin)) {
			return self::returnError(2002, self::$errorArray[2002]);
		}
		//判断账户状态
		if (intval($admin["status"]) !== 1) {
			return self::returnError(2005, self::$errorArray[2005]);
		}
		//更新该用户的token跟token时间
		$adminId = $admin['id'];
		$adminToken = self::__createToken($adminId);
		$adminTokenOverTime = time() + intval($_ENV['PROJECT_adminTokenOverTime']);
		$result = ManageAdmin::update(self::getConnection(), $adminId, array(
			'token' => $adminToken,
			'token_over_at' => intval($adminTokenOverTime),
			'nickname' => $nickname,
		));
		if (empty($result)) {
			return self::returnError(2003, self::$errorArray[2003]);
		}
		//返回
		return self::returnSuccess(array(
			'id' => $adminId,
			'token' => $adminToken,
			'name' => $nickname,
		));
	}


}