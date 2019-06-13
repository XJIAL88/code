<?php

namespace App\Services;

use App\Bases\BaseServiceAdmin;
use App\Bases\BaseServiceCzy;
use App\Component\ComponentCzyPower;
use App\Component\ComponentString;
use App\Manages\ManagePower;

/**
 * 业务 权限
 * User: Administrator
 * Date: 2016/7/28
 * Time: 16:06
 */
class ServiceAdminPower extends BaseServiceAdmin
{
	static $name = 'power';
	static $errorArray = array(
		'1001' => '权限ID不能为空',
		'1002' => '权限获取失败',
		'1003' => '接口名称不能为空',
		'1004' => '接口描述不能为空',
		'1005' => '接口类型不能为空',
		'1006' => '该接口权限已存在',
		'1007' => '添加接口权限失败',
		'1008' => '编辑接口权限失败',
		'1009' => '删除接口权限失败',
		'1010' => '权限类型不能为空',
		'1011' => '',
	);

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 权限


	////////////////////////////////////////////          单条数据          ////////////////////////////////////////////


	/**
	 * 获取权限
	 * @param int $id
	 * @param bool $isRead
	 * @return array
	 */
	static function powerGet($privilegeUuid = '')
	{
		//判断是否存在该权限
		if (empty($privilegeUuid)) {
			return self::returnError(self::$name . '1001', self::$errorArray["1001"]);
		}
		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return self::returnError(self::$name . '1011', $token["message"]);
		}
		$token = $token["data"];
		$result = ComponentCzyPower::qxwfwApplicationPrivilegeList('0', 2, $token, ['uuid'=>$privilegeUuid]);
		if ($result['code']) {
			return self::returnError(self::$name . '1002', self::$errorArray["1002"]);
		}
		if (!empty($result['data']['data'])){
			$result = self::keyChange($result['data']['data']);
			$data = self::arrayUnderLineToHump($result[0]);
		}else{
			$data = $result['data']['data'];
		}
		return self::returnSuccess($data);
	}

	/**
	 * 获取权限(缓存)
	 * @param int $id
	 * @return array
	 */
	static function powerGetByCache($privilegeUuid = '')
	{
		//获取缓存并返回
		return self::cache(__CLASS__ . '.' . __FUNCTION__, array(
			$privilegeUuid
		),
			function () use (
				$privilegeUuid
			) {
				return self::powerGet(
					$privilegeUuid,
					true
				);
			}, 30
		);
	}

	/**
	 * 清除单条缓存
	 * @param int $id
	 */
	static function powerGetByClean($privilegeUuid = '')
	{
		self::cleanCache(self::getCacheKey(__CLASS__ . '.powerGetByCache', array($privilegeUuid)));
	}


	////////////////////////////////////////////          多条数据          ////////////////////////////////////////////

	/**
	 * 获取权限列表
	 * @param $type
	 * @param string $name
	 * @param string $desc
	 * @param int $startAt
	 * @param int $endAt
	 * @param int $pageSize
	 * @param int $pageNumber
	 * @param bool $isRead
	 * @return array
	 */
	static function powerGetList($type, $parentUuid, $name = '', $desc = '', $startAt = 0, $endAt = 0, $pageSize, $pageNumber, $isRead = false)
	{
		//条件获取权限列表
		$wheres = array();
		if ($startAt) {
			$wheres['create_time'] = intval($startAt);
		}
		if ($endAt) {
			$wheres['end_time'] = intval($endAt);
		}
		if (!empty($name)) {
			$wheres['model'] = urlencode($name);
		}
		if (!empty($desc)) {
			$wheres['name'] = urlencode($desc);
		}
		if (!empty($parentUuid)){
			$wheres['parent_uuid'] = $parentUuid;
		}

		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return self::returnError(self::$name . '1011', $token["message"]);
		}
		$token = $token["data"];
		$result = ComponentCzyPower::qxwfwApplicationPrivilegeList($type,2,$token, $wheres, $pageNumber, $pageSize);
		if ((!isset($result["code"])) || (isset($result["code"]) && $result["code"] !== 0)) {
			return self::returnError(self::$name . '1011', $result["message"]);
		}
		if (empty($wheres)){
			//没有搜索条件是返回子结构
			$result = $result['data'];
		}else{
			//搜索 返回列表
			$result = $result['data']['data'];
		}
		//子结构为空时 ice返回空字符串 转成数组
		if (empty($result)){
			$data = [];
		}else{
			//键值转换
			$result = self::keyChange($result);
			//驼峰转换
			$data = self::arraysUnderLineToHump($result,'children');
		}


		return self::returnSuccess($data);
	}
	/**
	 * 获取多条（缓存）
	 * @param $type
	 * @param string $name
	 * @param string $desc
	 * @param int $startAt
	 * @param int $endAt
	 * @param int $pageSize
	 * @param int $pageNumber
	 * @return mixed
	 */
	static function powerGetListByCache($type, $parentUuid, $name = '', $desc = '', $startAt = 0, $endAt = 0, $pageSize = 0, $pageNumber = 1)
	{
		//获取缓存并返回
		return self::cache(__CLASS__ . '.' . __FUNCTION__, array(
			$type,
			$parentUuid, $name, $desc, $startAt, $endAt, $pageSize, $pageNumber
		),
			function () use (
				$type,
				$parentUuid, $name, $desc, $startAt, $endAt, $pageSize, $pageNumber
			) {
				return self::powerGetList(
					$type,
					$parentUuid, $name, $desc, $startAt, $endAt, $pageSize, $pageNumber, true
				);
			}, 30
		);
	}

	/**
	 *  获取多条（清除缓存）
	 * @param $type
	 */
	static function powerGetListByClean($type)
	{
		self::cleanCache(self::getCacheKey(__CLASS__ . '.powerGetListByCache', array($type)), true);
	}

	////////////////////////////////////////////          添加数据          ////////////////////////////////////////////

	/**
	 * 添加接口权限
	 * @param int $type
	 * @param string $name
	 * @param string $desc
	 * @return array
	 */
	static function powerInsert($type = 0, $parentUuid, $name = '', $desc = '')
	{
		//获取用户输入
		if (empty($name)) {
			return self::returnError(self::$name . '1003', self::$errorArray["1003"]);
		}
		if (empty($desc)) {
			return self::returnError(self::$name . '1004', self::$errorArray["1004"]);
		}
		if (empty($type)) {
			return self::returnError(self::$name . '1005', self::$errorArray["1005"]);
		}
		//添加接口权限
		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return self::returnError(self::$name . '1011', $token["message"]);
		}
		$token = $token["data"];
		$result = ComponentCzyPower::qxwfwApplicationPrivilegeCreate($type, $parentUuid, $desc, $name, $token);
		if ((!isset($result["code"])) || (isset($result["code"]) && $result["code"] !== 0)) {
			return self::returnError(self::$name . '1011', $result["message"]);
		}
		//清除权限列表缓存
		self::powerGetListByClean($type);
		//返回
		$data = self::arrayUnderLineToHump($result['data']);
		return self::returnSuccess($data);
	}

	////////////////////////////////////////////          编辑数据          ////////////////////////////////////////////

	/**
	 * 编辑权限
	 * @param int $id
	 * @param int $type
	 * @param string $name
	 * @param string $desc
	 * @return array
	 */
	static function powerUpdate($privilegeUuid = '', $parentUuid = '', $name = '', $desc = '')
	{
		//获取用户输入
		if (empty($name)) {
			return self::returnError(self::$name . '1003', self::$errorArray["1003"]);
		}
		if (empty($desc)) {
			return self::returnError(self::$name . '1004', self::$errorArray["1004"]);
		}
		//获取权限是否存在
		if (empty($privilegeUuid)) {
			return self::returnError(self::$name . '1001', self::$errorArray["1001"]);
		}
		$power = self::powerGet($privilegeUuid);
		if ($power['code']) {
			return self::returnError(self::$name . '1002', self::$errorArray["1002"]);
		}
		if (empty($parentUuid)){
			$parentUuid = $power['data']['parentUuid'];
		}
		//编辑权限
		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return self::returnError(self::$name . '1011', $token["message"]);
		}
		$token = $token["data"];
		$result = ComponentCzyPower::qxwfwApplicationPrivilegeEdit($privilegeUuid, $desc, $name, $parentUuid, $token);

		if ($result['code']) {
			return self::returnError(self::$name . '1008', $result["msg"]);
		}
		//清除缓存
		self::powerGetByClean($privilegeUuid);
		self::powerGetListByClean($power['data']["type"]);
		//返回
		return self::returnSuccess($result['data']);
	}

	////////////////////////////////////////////          删除数据          ////////////////////////////////////////////

	/**
	 * 删除接口权限
	 * @param int $id
	 * @return array
	 */
	static function powerDelete($privilegeUuid = 0)
	{
		//获取权限是否存在
		if (empty($privilegeUuid)) {
			return self::returnError(self::$name . '1001', self::$errorArray["1001"]);
		}
		$privilegeUuidInfo =ServiceAdminPower::powerGet($privilegeUuid);
		if ($privilegeUuidInfo['code']) {
			return self::returnError(self::$name . '1011', $privilegeUuidInfo['message']);
		}
		if (empty($privilegeUuidInfo['data'])) {
			return self::returnError(self::$name . '1001', self::$errorArray["1001"]);
		}

		//删除接口权限
		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return self::returnError(self::$name . '1011', $token["message"]);
		}
		$token = $token["data"];
		$result = ComponentCzyPower::qxwfwapplicationprivilegedel($privilegeUuid, $token);
		//清除缓存
		self::powerGetByClean($privilegeUuid);
		self::powerGetListByClean($privilegeUuidInfo['data']['type']);
		//返回
		return self::returnSuccess($result['data']);
	}

	/**
	 * 微服务返回结果$key值转换
	 */
	private static function keyChange($result)
	{
		foreach ($result as $k => $v){
			if (isset($v['name'])){
				$result[$k]['desc'] = $v['name'];
				unset($result[$k]['name']);
			}
			if (isset($v['model'])){
				$result[$k]['name'] = $v['model'];
				unset($result[$k]['model']);
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