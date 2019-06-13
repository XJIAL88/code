<?php

namespace App\Services;

use App\Bases\BaseServiceAdmin;
use App\Manages\ManageActivityGroup;
use Illuminate\Support\Facades\Crypt;


/**
 * 业务 分组管理
 * User: Administrator
 * Date: 2016/7/28
 * Time: 16:06
 */
class ServiceAdminActivityGroup extends BaseServiceAdmin
{
	static $name = '';
	static $errorArray = array(
		1000 => '',
		1001 => '分组标题不能为空',
		1002 => 'id不能为空',
		1003 => '分组获取失败',

		1100 => '添加分组失败',
		1101 => '已存在相同名字分组',

		1200 => '更新分组失败',

		1300 => '删除分组失败',
	);

	////////////////////////////////////////////          单条数据          ////////////////////////////////////////////

	/**
	 * 获取用户分组
	 * @param int $id
	 * @param bool $isRead
	 * @return array
	 */
	static function get($id = 0, $isRead = false)
	{
		if (empty($id)) {
			return self::returnError(self::$name . '1002', self::$errorArray[1002]);
		}
		$result = self::arObject2Array(ManageActivityGroup::get(self::getConnection($isRead), $id));
		if (empty($result)) {
			return self::returnError(self::$name . '1003', self::$errorArray[1003]);
		}
		return self::returnSuccess($result);
	}

	/**
	 * 获取用户分组（缓存）
	 * @param int $id
	 * @return array
	 */
	static function getByCache($id = 0)
	{
		return self::cache(__CLASS__ . '.' . __FUNCTION__, array(
			$id
		),
			function () use (
				$id
			) {
				return self::get(
					$id, true
				);
			}, 5
		);
	}

	/**
	 * 获取用户分组（清理缓存）
	 * @param int $id
	 * @return void
	 */
	static function getByClean($id = 0)
	{
		self::cleanCache(self::getCacheKey(__CLASS__ . '.getByCache', array($id)), true);
	}

	/**
	 * 获取用户分组（含分组下用户及用户所属分组）
	 * @param int $id
	 * @param string $keyword
	 * @return array
	 */
	static function getWithUsers($id = 0, $keyword = '')
	{
		// 获取分组
		$group = self::get($id);
		if ($group['code'] !== 0) {
			return $group;
		}
		$mobile = '';
		$nickname = '';
		if (preg_match('/^1[34578]\d{9}$/', trim($keyword))) {
			$mobile = $keyword;
		} else {
			$nickname = $keyword;
		}
		// 拼接用户
		$users = ServiceAdminActivityGroupUser::getListByCache($id, '', $mobile, $nickname);
		if ($users['code'] !== 0) {
			self::logError(__CLASS__ . '->' . __FUNCTION__, json_encode($users, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), 'group-error');
			return $users;
		}
		$users = $users['data'];
		$new_users = [];
		foreach ($users['dataList'] as $key => $value) {
			//{
			//	  "id": 7,
			//    "activityUserGroupId": 2,
			//    "activityUserGroupTitle": '',
			//    "userUuid": "",
			//    "image": "",
			//    "mobile": 10000000004,
			//    "nickname": "",
			//    "createAt": 1550210831,
			//    "updateAt": 0
			//}
			$info = [
				'id' => $value['id'],
				'mobile' => $value['mobile'],
				'nickname' => $value['nickname'],
				'image' => $value['image'],
				'createAt' => $value['createAt'],
			];
			$allGroups = ServiceAdminActivityGroupUser::getListByCache(0, '', $value['mobile']);
			if ($allGroups['code'] !== 0) {
				self::logError(__CLASS__ . '->' . __FUNCTION__, json_encode($allGroups, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), 'group-error');
				return $allGroups;
			}
			$allGroups = $allGroups['data'];
			$groupText = '';
			foreach ($allGroups['dataList'] as $k => $v) {
				if ($v['activityUserGroupId'] == 1) {
					continue;
				}
				$groupText = $groupText . $v['activityUserGroupTitle'] . ',';
			}
			$groupText = rtrim($groupText, ',');
			$info['groups'] = $groupText;
			$new_users[] = $info;
		}
		$group['data']['users'] = $new_users;
		return $group;
	}

	/**
	 * 获取用户分组数量（含分组下用户及用户所属分组）
	 * @param int $id
	 * @param string $keyword
	 * @return array
	 */
	static function getCountWithUsers($id = 0, $keyword = '')
	{
		// 获取分组
		$group = self::get($id);
		if ($group['code'] !== 0) {
			return $group;
		}
		$mobile = '';
		$nickname = '';
		if (preg_match('/^1[34578]\d{9}$/', trim($keyword))) {
			$mobile = $keyword;
		} else {
			$nickname = $keyword;
		}
		return ServiceAdminActivityGroupUser::getCount($id, '', $mobile, $nickname);
	}


	////////////////////////////////////////////          多条数据          ////////////////////////////////////////////

	/**
	 * 获取用户分组列表
	 * @param string $title
	 * @param int $pageSize
	 * @param int $pageNumber
	 * @param bool $isRead
	 * @return array
	 */
	static function getList($title = '', $pageSize = 0, $pageNumber = 1, $isRead = false)
	{
		$wheres = [];
		if ($title) {
			$wheres['title'] = ['LIKE', '%' . $title . '%'];
		}
		$dataList = self::arObjects2Array(ManageActivityGroup::getList(self::getConnection($isRead), $wheres, [], $pageSize, $pageNumber));
		foreach ($dataList as $key => $value) {
			$count = ServiceAdminActivityGroupUser::getCount($value['id']);
			if ($count['code'] !== 0) {
				return $count;
			}
			$dataList[$key]['userCount'] = $count['data'];
		}
		$dataCount = ManageActivityGroup::getCount(self::getConnection($isRead), $wheres);
		return self::returnSuccess(['dataList' => $dataList, 'dataCount' => $dataCount]);
	}

	/**
	 * 获取用户分组列表（缓存）
	 * @param string $title
	 * @return array
	 */
	static function getListByCache($title = '', $pageSize = 0, $pageNumber = 1)
	{
		return self::cache(__CLASS__ . '.' . __FUNCTION__, array(
			$title, $pageSize, $pageNumber
		),
			function () use (
				$title, $pageSize, $pageNumber
			) {
				return self::getList(
					$title, $pageSize, $pageNumber, true
				);
			}, 5
		);
	}

	/**
	 * 获取用户分组列表（清理缓存）
	 * @return void
	 */
	static function getListByClean()
	{
		self::cleanCache(self::getCacheKey(__CLASS__ . '.getListByCache', array()), true);
	}

	////////////////////////////////////////////          添加数据          ////////////////////////////////////////////

	/**
	 * 创建用户分组
	 * @param string $title
	 * @return array
	 */
	static function insert($title = '')
	{
		if (empty($title)) {
			return self::returnError(self::$name . '1001', self::$errorArray[1001]);
		}
		$data = [
			'title' => $title,
		];
		if (ManageActivityGroup::getCount(self::getConnection(), $data) > 0) {
			return self::returnError(self::$name . '1101', self::$errorArray[1101]);
		}
		$result = ManageActivityGroup::insert(self::getConnection(), $data);
		if (self::checkInsertResult($result) === false) {
			return self::returnError(self::$name . '1100', self::$errorArray[1100]);
		}
		//清除缓存
		self::clean();

		return self::returnSuccess(1);

	}

	////////////////////////////////////////////          编辑数据          ////////////////////////////////////////////

	/**
	 * 编辑用户分组
	 * @param int $id
	 * @param string $title
	 * @return array
	 */
	static function update($id = 0, $title = '')
	{
		if (empty($id)) {
			return self::returnError(self::$name . '1002', self::$errorArray[1002]);
		}
		if (empty($title)) {
			return self::returnError(self::$name . '1001', self::$errorArray[1001]);
		}
		$params = array(
			'title' => $title,
		);
		$result = ManageActivityGroup::update(self::getConnection(), $id, $params);
		if (self::checkUpdateResult($result) === false) {
			return self::returnError(self::$name . '1200', self::$errorArray[1200]);
		}
		//清除缓存
		self::clean($id);
		//返回
		return self::returnSuccess($result);
	}

	////////////////////////////////////////////          删除数据          ////////////////////////////////////////////

	/**
	 * 用户分组删除
	 * @param int $id
	 * @return array
	 */
	static function delete($id = 0)
	{
		if (empty($id)) {
			return self::returnError(self::$name . '1002', self::$errorArray[1002]);
		}
		$result = ManageActivityGroup::delete(self::getConnection(), $id);
		if (self::checkDeleteResult($result) === false) {
			return self::returnError(self::$name . '1300', self::$errorArray[1300]);
		}
		//清除缓存
		self::clean($id);
		//返回
		return self::returnSuccess($result);
	}

	/**
	 * 清除缓存
	 * @param int $id
	 * @return array
	 */
	static function clean($id = 0)
	{
		//清除缓存
		self::getListByClean();
		self::getByClean($id);
		//返回
		return self::returnSuccess(1);
	}
}