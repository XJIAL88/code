<?php

namespace App\Services;

use App\Bases\BaseServiceAdmin;
use App\Bases\BaseServiceCzy;
use App\Component\ComponentCzyEmployee;
use App\Component\ComponentCzyPower;
use App\Component\ComponentCzyRole;
use App\Manages\ManageActionRecord;
use App\Manages\ManageAdmin;
use App\Manages\ManageRole;

/**
 * 业务 账户管理
 * User: Administrator
 * Date: 2016/7/28
 * Time: 16:06
 */
class ServiceAdminAdmin extends BaseServiceAdmin
{
	static $name = 'admin';
	static $errorArray = array(
		'1001' => '账户ID不能为空',
		'1002' => '账户获取失败',
		'1003' => '角色ID不能为空',
		'1004' => '角色获取失败',
		'1005' => '账户类型不能为空',
		'1006' => '账户名不能为空',
		'1007' => '账户密码不能为空',
		'1008' => '账户名已存在',
		'1009' => '账户状态不能为空',
		'1010' => '账户创建失败',
		'1011' => '账户编辑失败',
		'1012' => '账户删除失败',
		'1013' => '账户原密码不能为空',
		'1014' => '账户新密码不能为空',
		'1015' => '账户原密码错误',
		'2001' => '',
	);

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 账户管理


	////////////////////////////////////////////          单条数据          ////////////////////////////////////////////

	/**
	 * 获取账户
	 * @param int $id
	 * @param bool $isRead
	 * @return array
	 */
	static function adminGet($id = 0, $isRead = false)
	{
		//获取账户信息
		if (empty($id)) {
			return self::returnError(self::$name . '1001', self::$errorArray["1001"]);
		}
		$whereExit = array(
			'id' => $id,
		);
		$data = self::arObject2Array(ManageAdmin::getWithWheres(self::getConnection($isRead), $whereExit));
		if (empty($data)) {
			return self::returnError(self::$name . '1002', self::$errorArray["1002"]);
		}
		//返回
		return self::returnSuccess($data);
	}

	/**
	 * 获取账户(缓存)
	 * @param int $id
	 * @return mixed
	 */
	static function adminGetByCache($id = 0)
	{
		//获取缓存并返回
		return self::cache(__CLASS__ . '.' . __FUNCTION__, array(
			$id
		),
			function () use (
				$id
			) {
				return self::adminGet(
					$id,
					true
				);
			}, 30
		);
	}

	/**
	 * 清除单条缓存
	 * @param int $id
	 */
	static function adminGetByClean($id = 0)
	{
		self::cleanCache(self::getCacheKey(__CLASS__ . '.adminGetByCache', array($id)));
	}


	////////////////////////////////////////////          多条数据          ////////////////////////////////////////////

	/**
	 * 获取账户列表
	 * @param int $roleUuid
	 * @param string $username
	 * @param int $status
	 * @param int $startAt
	 * @param int $endAt
	 * @param int $pageSize
	 * @param int $pageNumber
	 * @param bool $isRead
	 * @return array
	 */
	static function adminGetList($roleUuid = 0, $username = '', $status = 0, $startAt = 0, $endAt = 0, $pageSize = 0, $pageNumber = 1, $isRead = false)
	{
		//条件获取账户列表
		$wheres = array();
		if ($startAt) {
			$wheres[] = array('create_at', '>=', intval($startAt));
		}
		if ($endAt) {
			$wheres[] = array('create_at', '<=', intval($endAt));
		}
		if ($roleUuid) {
			$wheres['role_uuid'] = intval($roleUuid);
		}
		if ($status) {
			$wheres['status'] = intval($status);
		}
		if ($username) {
			$wheres['username'] = array('LIKE', '%' . $username . '%');
		}
		$orders = array('id' => 'DESC');
		$dataCount = ManageAdmin::getCount(self::getConnection($isRead), $wheres);
		$dataList = self::arObjects2Array(ManageAdmin::getList(self::getConnection($isRead), $wheres, $orders, $pageSize, $pageNumber));
		return self::returnSuccess(array('dataList' => $dataList, 'dataCount' => $dataCount));

	}

	/**
	 * 获取多条（缓存）
	 * @param int $roleUuid
	 * @param string $username
	 * @param int $status
	 * @param int $startAt
	 * @param int $endAt
	 * @param int $pageSize
	 * @param int $pageNumber
	 * @return mixed
	 */
	static function adminGetListByCache($roleUuid = 0, $username = '', $status = 0, $startAt = 0, $endAt = 0, $pageSize = 0, $pageNumber = 1)
	{
		//获取缓存并返回
		return self::cache(__CLASS__ . '.' . __FUNCTION__, array(
			$roleUuid, $username, $status, $startAt, $endAt, $pageSize, $pageNumber
		),
			function () use (
				$roleUuid, $username, $status, $startAt, $endAt, $pageSize, $pageNumber
			) {
				return self::adminGetList(
					$roleUuid, $username, $status, $startAt, $endAt, $pageSize, $pageNumber
				);
			}, 30
		);
	}

	/**
	 *  获取多条（清除缓存）
	 */
	static function adminGetListByClean()
	{
		self::cleanCache(self::getCacheKey(__CLASS__ . '.adminGetListByCache', array(0)), true);
	}

	////////////////////////////////////////////          添加数据          ////////////////////////////////////////////

	/**
	 * 创建账户
	 * @param int $roleUuid
	 * @param string $username
	 * @param int $status
	 * @return array
	 */
	static function adminInsert($roleUuid = 0, $username = '', $status = 0)
	{
		//判断角色是否存在
		if (empty($roleUuid)) {
			return self::returnError(self::$name . '1003', self::$errorArray["1003"]);
		}
		//判断账户基本信息
		if (empty($username)) {
			return self::returnError(self::$name . '1006', self::$errorArray["1006"]);
		}
		if (empty($status)) {
			return self::returnError(self::$name . '1009', self::$errorArray["1009"]);
		}
		//判断账户是否存在
		$admin = self::arObject2Array(ManageAdmin::getWith(self::getConnection(), 'username', $username));
		if ($admin) {
			return self::returnError(self::$name . '1008', self::$errorArray["1008"]);
		}

		//获取调取ICE接口参数配置
		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return self::returnError(self::$name . '2001', $token["message"]);
		}
		$token = $token["data"];
		$role = ComponentCzyRole::qxwfwApplicationRoleList($token, ['uuid' => $roleUuid]);
		if ($role["code"] !== 0) {
			return self::returnError(self::$name . '2001', $role["message"]);
		}
		if (empty($role['data']['data'])) {
			return self::returnError(self::$name . '1004', self::$errorArray["1004"]);
		}
		$role = $role['data']['data'][0];

		//判断彩之云账户是否合法
		$getCzyAccount = ComponentCzyEmployee::employeeAccount("", $username, $token);
		if ((!isset($getCzyAccount["code"])) || (isset($getCzyAccount["code"]) && $getCzyAccount["code"] !== 0)) {
			return self::returnError(self::$name . '2001', $getCzyAccount["message"]);
		}
		//转化
		$getCzyAccount = $getCzyAccount["data"];
		if (!isset($getCzyAccount["username"])) {
			return self::returnError(self::$name . '2001', $getCzyAccount);
		}

		//创建账户
		$params = array(
			'role_uuid' => $roleUuid,
			'role_name' => $role['name'],
			'username' => $username,
			'nickname' => $username,
			'status' => $status,
		);
		$result = ManageAdmin::insert(self::getConnection(), $params);
		if (self::checkInsertResult($result) === false) {
			return self::returnError(self::$name . '1010', self::$errorArray["1010"]);
		}
		//角色用户关联
//		$res = ComponentCzyRole::qxwfwRoleUserCreate($roleUuid, $username, $user_uuid, $token);
//		if ($res["code"] !== 0) {
//			return self::returnError(self::$name . '2001', $res["message"]);
//		}
		//清除缓存
		self::adminGetListByClean();
		//返回
		return self::returnSuccess($result);
	}

	////////////////////////////////////////////          编辑数据          ////////////////////////////////////////////

	/**
	 * 编辑账户
	 * @param int $id
	 * @param int $roleUuid
	 * @param int $status
	 * @return array
	 */
	static function adminUpdate($id = 0, $roleUuid = 0, $status = 0)
	{
		//获取账户信息
		if (empty($id)) {
			return self::returnError(self::$name . '1001', self::$errorArray["1001"]);
		}
		$whereExit = array(
			'id' => $id,
		);
		$data = self::arObject2Array(ManageAdmin::getWithWheres(self::getConnection(), $whereExit));
		if (empty($data)) {
			return self::returnError(self::$name . '1002', self::$errorArray["1002"]);
		}
		//判断账户基本信息
		if (empty($status)) {
			return self::returnError(self::$name . '1009', self::$errorArray["1009"]);
		}

		//判断角色是否存在
		if (empty($roleUuid)) {
			return self::returnError(self::$name . '1003', self::$errorArray["1003"]);
		}
		//获取调取ICE接口参数配置
		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return self::returnError(self::$name . '2001', $token["message"]);
		}
		$token = $token["data"];
		$role = ComponentCzyRole::qxwfwApplicationRoleList($token, ['uuid' => $roleUuid]);
		if ($role["code"] !== 0) {
			return self::returnError(self::$name . '2001', $role["message"]);
		}
		if (empty($role['data']['data'])) {
			return self::returnError(self::$name . '1004', self::$errorArray["1004"]);
		}
		$role = $role['data']['data'][0];
		//编辑账户
		$params = array(
			'status' => $status,
			'role_uuid' => $roleUuid,
			'role_name' => $role['name'],
		);
		$result = ManageAdmin::update(self::getConnection(), $id, $params);
		if (self::checkUpdateResult($result) === false) {
			return self::returnError(self::$name . '1011', self::$errorArray["1011"]);
		}
		//清除缓存
		self::adminGetListByClean();
		self::adminGetByClean($id);
		//返回
		return self::returnSuccess($result);
	}

	/**
	 * 绑定商户
	 * @param int $id
	 * @param int $roleUuid
	 * @param int $chageTraderId
	 * @return array
	 */
	static function adminBindTrader($mobile = '', $roleUuid = 0, $chageTraderId = 0)
	{
		//获取账户信息
		if (empty($mobile)) {
			return self::returnError(self::$name . '1001', self::$errorArray["1001"]);
		}
		$whereExit = array(
			'username' => $mobile,
		);
		$data = self::arObject2Array(ManageAdmin::getWithWheres(self::getConnection(), $whereExit));
		if (empty($data)) {
			return self::returnError(self::$name . '1002', self::$errorArray["1002"]);
		}
		//判断账户基本信息
		if (empty($chageTraderId)) {
			return self::returnError(self::$name . '2001', '商户id不能为空');
		}
		//判断角色是否存在
		if (empty($roleUuid)) {
			return self::returnError(self::$name . '1003', self::$errorArray["1003"]);
		}
		//获取调取ICE接口参数配置
		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return self::returnError(self::$name . '2001', $token["message"]);
		}
		$token = $token["data"];
		$role = ComponentCzyRole::qxwfwApplicationRoleList($token, ['uuid' => $roleUuid]);
		if ($role["code"] !== 0) {
			return self::returnError(self::$name . '2001', $role["message"]);
		}
		if (empty($role['data']['data'])) {
			return self::returnError(self::$name . '1004', self::$errorArray["1004"]);
		}
		$role = $role['data']['data'][0];
		//编辑账户
		$params = array(
			'trader_id' => $chageTraderId,
			'role_uuid' => $roleUuid,
			'role_name' => $role['name'],
		);
		$result = ManageAdmin::update(self::getConnection(), $data['id'], $params);
		if (self::checkUpdateResult($result) === false) {
			return self::returnError(self::$name . '1011', self::$errorArray["1011"]);
		}

		//返回
		return self::returnSuccess($result);
	}

	/**
	 * 修改账户密码
	 * @param int $adminId
	 * @param string $password
	 * @param string $newPassword
	 * @return array
	 */
	static function adminUpdatePassword($adminId = 0, $password = "", $newPassword = "")
	{
		//判断用户输入
		if (empty($adminId)) {
			return self::returnError(self::$name . '1001', self::$errorArray["1001"]);
		}
		if (empty($password)) {
			return self::returnError(self::$name . '1013', self::$errorArray["1013"]);
		}
		if (empty($newPassword)) {
			return self::returnError(self::$name . '1014', self::$errorArray["1014"]);
		}
		//获取用户信息
		$admin = self::arObject2Array(ManageAdmin::get(self::getConnection(), $adminId));
		if (empty($admin)) {
			return self::returnError(self::$name . '1002', self::$errorArray["1002"]);
		}
		//判断密码是否正确
		$password = self::__encryptPassword($password, $admin["secret"]);
		if ($password !== $admin["password"]) {
			return self::returnError(self::$name . '1015', self::$errorArray["1015"]);
		}
		$newPassword = self::__encryptPassword($newPassword, $admin["secret"]);
		//修改用户密码
		$params = array(
			'password' => $newPassword
		);
		$result = ManageAdmin::update(self::getConnection(), $adminId, $params);
		//返回
		return self::returnSuccess($result);
	}

	////////////////////////////////////////////          删除数据          ////////////////////////////////////////////

	/**
	 * 账户删除
	 * @param int $id
	 * @return array
	 */
	static function adminDelete($id = 0)
	{
		//获取账户信息
		if (empty($id)) {
			return self::returnError(self::$name . '1001', self::$errorArray["1001"]);
		}
		$whereExit = array(
			'id' => $id,
		);
		$data = self::arObject2Array(ManageAdmin::getWithWheres(self::getConnection(), $whereExit));
		if (empty($data)) {
			return self::returnError(self::$name . '1002', self::$errorArray["1002"]);
		}
		//删除账户
		$result = ManageAdmin::delete(self::getConnection(), $id);
		if (self::checkDeleteResult($result) === false) {
			return self::returnError(self::$name . '1012', self::$errorArray["1012"]);
		}
		//清除缓存
		self::adminGetListByClean();
		self::adminGetByClean($id);
		//返回
		return self::returnSuccess($result);
	}

	/**
	 * 账户权限列表
	 * @param $name
	 * @return array|mixed
	 */
	static function adminGetPowerList($userUuid)
	{
		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return self::returnError(self::$name . '1011', $token["message"]);
		}
		$token = $token["data"];
		$result = ComponentCzyPower::qxwfwApplicationUserPrivilegeList($userUuid, $token);
		if ((!isset($result["code"])) || (isset($result["code"]) && $result["code"] !== 0)) {
			return self::returnError(self::$name . '1011', $result["message"]);
		}

		if (!empty($result['data'])) {
			//驼峰转换
			$data = self::arraysUnderLineToHump($result['data']);
		} else {
			$data = $result['data'];
		}
		return self::returnSuccess($data);
	}

	/**
	 * 管理员操作记录
	 * @param $adminId
	 * @param $adminName
	 * @param $number
	 * @param $pageSize
	 * @param $pageNumber
	 * @return array
	 */
	static function adminGetActionRecordList($adminId = 0, $adminName = '', $number = '', $pageSize = 10, $pageNumber = 0)
	{
		$where = array();
		if (!empty($adminId)) {
			$where['admin_id'] = $adminId;
		}
		if (!empty($adminId)) {
			$where['admin_name'] = $adminName;
		}
		if (!empty($adminId)) {
			$where['number'] = $number;
		}
		$dataCount = ManageActionRecord::getCount(self::getConnection(), $where);
		$dataList = self::arObjects2Array(ManageActionRecord::getList(self::getConnection(), $where, [], $pageSize, $pageNumber));
		return self::returnSuccess([
			'dataCount' => $dataCount,
			'dataList' => $dataList,
		]);
	}
}