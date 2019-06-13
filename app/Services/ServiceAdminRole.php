<?php

namespace App\Services;

use App\Bases\BaseServiceAdmin;
use App\Bases\BaseServiceCzy;
use App\Component\ComponentCzyRole;
use App\Manages\ManageAdmin;
use App\Manages\ManageRole;

/**
 * 业务 角色
 * User: Administrator
 * Date: 2016/7/28
 * Time: 16:06
 */
class ServiceAdminRole extends BaseServiceAdmin
{
	static $name = 'role';
	static $errorArray = array(
		'1001' => '角色ID不能为空',
		'1002' => '角色获取失败',
		'1003' => '商户ID不能为空',
		'1004' => '角色标题不能为空',
		'1005' => '角色类型不能为空',
		'1006' => '角色添加失败',
		'1007' => '角色编辑失败',
		'1008' => '存在关联数据,无法删除',
		'1009' => '角色删除失败',
		'1010' => '角色名称已存在',
	);

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 角色


	////////////////////////////////////////////          单条数据          ////////////////////////////////////////////

	/**
	 * 获取角色
	 * @param int $roleUuid
	 * @return array
	 */
	static function roleGet($roleUuid)
	{
		//判断是否存在该角色
		if (empty($roleUuid)) {
			return self::returnError(self::$name . '1001', self::$errorArray["1001"]);
		}
		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return self::returnError(self::$name . '1011', $token["message"]);
		}
		$token = $token["data"];
		$result = ComponentCzyRole::qxwfwApplicationRoleList($token, ['uuid'=>$roleUuid]);
		if (!isset($result['code'])||(isset($result['code'])&&$result['code']!==0)){
			return self::returnError(self::$name . '1011', $result["message"]);
		}
		if (!empty($result['data']['data'])){
			//键值转换
			$result = self::keyChange($result['data']['data']);
			//返回
			$data = self::arrayUnderLineToHump($result[0]);
			//角色拥有的权限
			$power = ComponentCzyRole::qxwfwApplicationRolePrivilegeList('2', $roleUuid, $token);
			if ($power['code']){
				return self::returnError(self::$name . '1011', $power['message']);
			}
			if (empty($power['data'])){
				$data['power'] = [];
			}else{
				foreach ($power['data'] as $item){
					$powers[] = $item['uuid'];
				}
				$data['power'] = $powers;
			}
		}else{
			$data = [];
		}
		return self::returnSuccess($data);
	}

	/**
	 * 获取角色(缓存)
	 * @param int $traderId
	 * @param int $id
	 * @return mixed
	 */
	static function roleGetByCache($roleUuid)
	{
		//获取缓存并返回
		return self::cache(__CLASS__ . '.' . __FUNCTION__, array(
			$roleUuid
		),
			function () use (
				$roleUuid
			) {
				return self::roleGet(
					$roleUuid
				);
			}, 30
		);
	}

	/**
	 * 清除单条缓存
	 * @param int $traderId
	 * @param int $id
	 */
	static function roleGetByClean($roleUuid)
	{
		self::cleanCache(self::getCacheKey(__CLASS__ . '.roleGetByCache', array($roleUuid)));
	}


	////////////////////////////////////////////          多条数据          ////////////////////////////////////////////

	/**
	 * 获取角色列表
	 * @param string $title
	 * @param int $super
	 * @param int $startAt
	 * @param int $endAt
	 * @param int $pageSize
	 * @param int $pageNumber
	 * @param bool $isRead
	 * @return array
	 */
	static function roleGetList($title, $uuid = '', $super = 0, $startAt = 0, $endAt = 0, $pageSize = 10, $pageNumber = 1, $otherTraderId)
	{

		//条件获取权限列表
		$wheres = array();
		if ($startAt) {
			$wheres['create_time'] = intval($startAt);
		}
		if ($endAt) {
			$wheres['end_time'] = intval($endAt);
		}
		if ($title) {
			$wheres['name'] = urlencode($title);
		}
		if ($super) {
			$wheres['type'] = intval($super);
		}
		if ($uuid) {
			$wheres['uuid'] = $uuid;
		}

		if (!empty($otherTraderId)){
			$wheres['extend'] = $otherTraderId;
		}

		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return self::returnError(self::$name . '1011', $token["message"]);
		}
		$token = $token["data"];
		$result = ComponentCzyRole::qxwfwApplicationRoleList($token,$wheres, $pageNumber, $pageSize);
		if (!isset($result['code'])||(isset($result['code'])&&$result['code']!==0)){
			return self::returnError(self::$name . '1011', $result["message"]);
		}
		if (!empty($result['data']['data'])){
			$result = self::keyChange($result['data']['data']);
			$data = self::arraysUnderLineToHump($result);
		}else{
			$data = [];
		}

		return self::returnSuccess($data);
	}

	/**
	 * 获取多条（缓存）
	 * @param string $title
	 * @param int $super
	 * @param int $startAt
	 * @param int $endAt
	 * @param int $pageSize
	 * @param int $pageNumber
	 * @return mixed
	 */
	static function roleGetListByCache($title = '', $uuid='', $super = 0, $startAt = 0, $endAt = 0, $pageSize = 0, $pageNumber = 1, $otherTraderId = 0)
	{
		//获取缓存并返回
		return self::cache(__CLASS__ . '.' . __FUNCTION__, array(
			$title, $uuid, $super, $startAt, $endAt, $pageSize, $pageNumber, $otherTraderId
		),
			function () use (
				$title, $uuid, $super, $startAt, $endAt, $pageSize, $pageNumber, $otherTraderId
			) {
				return self::roleGetList(
					$title, $uuid, $super, $startAt, $endAt, $pageSize, $pageNumber, $otherTraderId
				);
			}, 30
		);
	}

	/**
	 *  获取多条（清除缓存）
	 */
	static function roleGetListByClean()
	{
		self::cleanCache(self::getCacheKey(__CLASS__ . '.roleGetListByCache', array(0)), true);
	}

	////////////////////////////////////////////          添加数据          ////////////////////////////////////////////

	/**
	 * 添加角色
	 * @param int $traderId
	 * @param string $title
	 * @param array $power
	 * @param int $super
	 * @return array
	 */
	static function roleInsert($title = '', $power = array(), $super)
	{
		//判断用户输入
		if (empty($title)) {
			return self::returnError(self::$name . '1004', self::$errorArray["1004"]);
		}
		//$power = ['b591ae7eeeb340d78ce259a653b5ed02'];
		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return self::returnError(self::$name . '1011', $token["message"]);
		}
		$token = $token["data"];

		$roleCreated = ComponentCzyRole::qxwfwRoleDetail($title, $token);
		//请求角色详情接口错误
		//角色已存在
		if ($roleCreated['code'] === 0){
			return self::returnError(self::$name . '1010', self::$errorArray['1010']);
		}
		$result = ComponentCzyRole::qxwfwRoleCreate($title, $super, 0, $token);
		if (!empty($power)){
			for ($i=0;$i<count($power);$i++){
				ComponentCzyRole::qxwfwRolePrivilegeCreate($power[$i],$result['data']['uuid'],$token);
			}
		}
		$result = self::keyChange($result['data']);
		$data = self::arrayUnderLineToHump($result);

		//清除缓存
		self::roleGetListByClean();
		return self::returnSuccess($data);
	}
	/**
	 * 角色权限列表
	 * @param $name
	 * @return array|mixed
	 */
	static function roleGetPowerList($roleUuid)
	{
		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return self::returnError(self::$name . '1011', $token["message"]);
		}
		$token = $token["data"];
		$result = ComponentCzyRole::qxwfwApplicationRolePrivilegeList('2', $roleUuid, $token);
		if ((!isset($result["code"])) || (isset($result["code"]) && $result["code"] !== 0)) {
			return self::returnError(self::$name . '1011', $result["message"]);
		}
		if (!empty($result['data'])){
			$result = self::keyChange($result['data']);
			$data = self::arraysUnderLineToHump($result);
		}else{
			$data = $result['data'];
		}
		return self::returnSuccess($data);
	}
	/**
	 * 角色用户列表
	 * @param $name
	 * @return array|mixed
	 */
	static function roleGetUserList($roleUuid)
	{
		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return self::returnError(self::$name . '1011', $token["message"]);
		}
		$token = $token["data"];
		$result = ComponentCzyRole::qxwfwRoleUserList($roleUuid, $token);
		if ((!isset($result["code"])) || (isset($result["code"]) && $result["code"] !== 0)) {
			return self::returnError(self::$name . '1011', $result["message"]);
		}
		if (!empty($result['data'])){
			$result = self::keyChange($result['data']);
			$data = self::arraysUnderLineToHump($result);
		}else{
			$data = $result['data'];
		}
		return self::returnSuccess($data);
	}

	////////////////////////////////////////////          编辑数据          ////////////////////////////////////////////

	/**
	 * 编辑角色
	 * @param int $id
	 * @param string $title
	 * @param array $power
	 * @param int $super
	 * @return array
	 */
	static function roleUpdate($roleUuid = 0, $title = '', $type = 0, $power = array())
	{
		//判断是否存在该角色
		if (empty($roleUuid)) {
			return self::returnError(self::$name . '1001', self::$errorArray["1001"]);
		}
		$role = self::roleGetByCache($roleUuid);
		if ($role['code']) {
			return self::returnError(self::$name . '1002', self::$errorArray["1002"]);
		}
		//判断用户输入
		if (empty($title)) {
			return self::returnError(self::$name . '1004', self::$errorArray["1004"]);
		}
		if (empty($type)) {
			return self::returnError(self::$name . '1005', self::$errorArray["1005"]);
		}

		$addPower = array_diff($power,$role['data']['power']);
		$delPower = array_diff($role['data']['power'],$power);

		//编辑角色
		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return self::returnError(self::$name . '1011', $token["message"]);
		}
		$token = $token["data"];
		$result = ComponentCzyRole::qxwfwRoleEdit($roleUuid, $title, $type, $token);
		if (!empty($addPower)){
			foreach ($addPower as $k => $v){
				ComponentCzyRole::qxwfwRolePrivilegeCreate($addPower[$k],$roleUuid,$token);
			}
		}
		if (!empty($delPower)){
			foreach ($delPower as $k => $v){
				ComponentCzyRole::qxwfwRolePrivilegeDel($delPower[$k], $roleUuid, $token);
			}
		}
		if ($result['code']) {
			return self::returnError(self::$name . '1007', $result['message']);
		}
		//清除缓存
		self::roleGetByClean($roleUuid);
		self::roleGetListByClean();
		//返回
		return self::returnSuccess($result['data']);

	}

	////////////////////////////////////////////          删除数据          ////////////////////////////////////////////

	/**
	 * 删除角色
	 * @param int $id
	 * @return array
	 */
	static function roleDelete($roleUuid)
	{
		//判断是否存在该角色
		if (empty($roleUuid)) {
			return self::returnError(self::$name . '1001', self::$errorArray["1001"]);
		}
		$admin = ManageAdmin::getWith(self::getConnection(),'role_uuid',$roleUuid);
		if (!empty($admin)){
			return self::returnError(self::$name . '1008', self::$errorArray['1008']);
		}
		//删除角色
		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return self::returnError(self::$name . '1011', $token["message"]);
		}
		$token = $token["data"];
		$result = ComponentCzyRole::qxwfwRoleDel($roleUuid, $token);

		//清除缓存
		self::roleGetByClean($roleUuid);
		self::roleGetListByClean();
		//返回
		return self::returnSuccess($result['data']);
	}

	/**
	 * 角色详情
	 * @param $roleName
	 * @return array
	 */
	static function roleDetail($roleName)
	{
		if (empty($roleName)) {
			return self::returnError(self::$name . '1004', self::$errorArray["1004"]);
		}
		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return self::returnError(self::$name . '1011', $token["message"]);
		}
		$token = $token["data"];

		return  ComponentCzyRole::qxwfwRoleDetail($roleName, $token);
	}
	/**
	 * 微服务返回结果$key值转换
	 */
	private static function keyChange($result)
	{
		foreach ($result as $k => $v){
			if (isset($v['name'])){
				$result[$k]['title'] = $v['name'];
				unset($result[$k]['name']);
			}
			if (isset($v['type'])){
				$result[$k]['super'] = $v['type'];
				unset($result[$k]['type']);
			}
			if (isset($v['create_time'])){
				$result[$k]['create_at'] = $v['create_time'];
				unset($result[$k]['create_time']);
			}
			if (isset($v['update_time'])){
				$result[$k]['update_at'] = $v['update_time'];
				unset($result[$k]['update_time']);
			}
			if (isset($v['children'])&&!empty($v['children'])){
				$result[$k]['children'] = self::keyChange($v['children']);
			}
		}
		return $result;
	}
}