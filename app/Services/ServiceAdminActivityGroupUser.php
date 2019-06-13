<?php

namespace App\Services;

use App\Bases\BaseKernel;
use App\Bases\BaseServiceAdmin;
use App\Bases\BaseServiceCzy;
use App\Component\ComponentCzyEmployee;
use App\Component\ComponentFile;
use App\Manages\ManageActivityGroupUser;


/**
 * 业务 分组用户管理
 * User: Administrator
 * Date: 2016/7/28
 * Time: 16:06
 */
class ServiceAdminActivityGroupUser extends BaseServiceAdmin
{
	static $name = '';
	static $errorArray = array(
		1000 => '',
		1001 => '手机号不能为空',
		1002 => 'id不能为空',
		1003 => '分组用户获取失败',
		1004 => '分组id不能为空',
		1005 => '不能离开此分组',

		1100 => '添加分组用户失败',
		1101 => '分组不存在',

		1200 => '更新分组用户失败',

		1300 => '删除分组用户失败',
	);

	////////////////////////////////////////////          单条数据          ////////////////////////////////////////////

	/**
	 * 获取分组用户
	 * @param int $id
	 * @param bool $isRead
	 * @return array
	 */
	static function get($id = 0, $isRead = false)
	{
		if (empty($id)) {
			return self::returnError(self::$name . '1002', self::$errorArray[1002]);
		}
		$result = self::arObject2Array(ManageActivityGroupUser::get(self::getConnection($isRead), $id));
		if (empty($result)) {
			return self::returnError(self::$name . '1003', self::$errorArray[1003]);
		}
		return self::returnSuccess($result);
	}

	/**
	 * 获取分组用户（缓存）
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
	 * 获取分组用户（清理缓存）
	 * @param int $id
	 * @return void
	 */
	static function getByClean($id = 0)
	{
		self::cleanCache(self::getCacheKey(__CLASS__ . '.getByCache', array($id)), true);
	}


	////////////////////////////////////////////          数量          ////////////////////////////////////////////

	/**
	 * 获取分组用户数量
	 * @param int $activityUserGroupId
	 * @param string $userUuid
	 * @param string $mobile
	 * @param string $nickname
	 * @param bool $isRead
	 * @return array
	 */
	static function getCount($activityUserGroupId = 0, $userUuid = '', $mobile = '', $nickname = '', $isRead = false)
	{
		$wheres = [];
		if ($activityUserGroupId) {
			$wheres['activity_user_group_id'] = $activityUserGroupId;
		}
		if ($userUuid) {
			$wheres['user_uuid'] = $userUuid;
		}
		if ($mobile) {
			$wheres['mobile'] = $mobile;
		}
		if ($nickname) {
			$wheres['nickname'] = $nickname;
		}
		return self::returnSuccess(ManageActivityGroupUser::getCount(self::getConnection($isRead), $wheres));
	}

	////////////////////////////////////////////          多条数据          ////////////////////////////////////////////

	/**
	 * 获取分组用户列表
	 * @param int $activityUserGroupId
	 * @param string $userUuid
	 * @param string $mobile
	 * @param string $nickname
	 * @param int $pageSize
	 * @param int $pageNumber
	 * @param bool $isRead
	 * @return array
	 */
	static function getList($activityUserGroupId = 0, $userUuid = '', $mobile = '', $nickname = '', $pageSize = 0, $pageNumber = 1, $isRead = false)
	{
		$wheres = [];
		if ($activityUserGroupId) {
			$wheres['activity_user_group_id'] = $activityUserGroupId;
		}
		if ($userUuid) {
			$wheres['user_uuid'] = $userUuid;
		}
		if ($mobile) {
			$wheres['mobile'] = $mobile;
		}
		if ($nickname) {
			$wheres['nickname'] = $nickname;
		}
		$dataList = self::arObjects2Array(ManageActivityGroupUser::getList(self::getConnection($isRead), $wheres, [], $pageSize, $pageNumber));
		$dataCount = ManageActivityGroupUser::getCount(self::getConnection($isRead), $wheres);
		return self::returnSuccess(['dataList' => $dataList, 'dataCount' => $dataCount]);
	}

	/**
	 * 获取分组用户列表（缓存）
	 * @param int $activityUserGroupId
	 * @param string $userUuid
	 * @param string $mobile
	 * @param string $nickname
	 * @return array
	 */
	static function getListByCache($activityUserGroupId = 0, $userUuid = '', $mobile = '', $nickname = '', $pageSize = 0, $pageNumber = 1)
	{
		return self::cache(__CLASS__ . '.' . __FUNCTION__, array(
			$activityUserGroupId, $userUuid, $mobile, $nickname, $pageSize, $pageNumber
		),
			function () use (
				$activityUserGroupId, $userUuid, $mobile, $nickname, $pageSize, $pageNumber
			) {
				return self::getList(
					$activityUserGroupId, $userUuid, $mobile, $nickname, $pageSize, $pageNumber, true
				);
			}, 5
		);
	}

	/**
	 * 获取分组用户列表（清理缓存）
	 * @return void
	 */
	static function getListByClean()
	{
		self::cleanCache(self::getCacheKey(__CLASS__ . '.getListByCache', array()), true);
	}

	////////////////////////////////////////////          添加数据          ////////////////////////////////////////////

	/**
	 * 创建分组用户
	 * @param int $activityUserGroupId
	 * @param string $mobile
	 * @return array
	 */
	static function insert($activityUserGroupId = 1, $mobile = '')
	{
		if (empty($mobile)) {
			return self::returnError(self::$name . '1001', self::$errorArray[1001]);
		}
		$token = BaseServiceCzy::getAppToken();
		if ($token['code'] !== 0) {
			return self::returnError(self::$name . '1011', $token['message']);
		}
		$token = $token['data'];
		$user = ComponentCzyEmployee::customerGetInfo($mobile, $token);
		if ($user['code'] !== 0) {
			return self::returnError(self::$name . '1000', $user['message']);
		}
		$user = $user['data'];
		$userUuid = $user['uuid'];
		$nickname = $user['nick_name'];
		$activityUserGroupId = empty($activityUserGroupId) ? 1 : $activityUserGroupId;
		$group = ServiceAdminActivityGroup::get($activityUserGroupId);
		if ($group['code'] !== 0) {
			return self::returnError(self::$name . '1101', self::$errorArray[1101]);
		}
		$group = $group['data'];
		$data = [
			'mobile' => $mobile,
			'activity_user_group_id' => $activityUserGroupId,
			'activity_user_group_title' => $group['title'],
		];
		if (ManageActivityGroupUser::getCount(self::getConnection(), $data) > 0) {
			// 重复添加视为成功，返回2只为作识别
			return self::returnSuccess(2);
		}
		if ($userUuid) {
			$data['user_uuid'] = $userUuid;
		}
		if ($nickname) {
			$data['nickname'] = $nickname;
		}
		$result = ManageActivityGroupUser::insert(self::getConnection(), $data);
		if (self::checkInsertResult($result) === false) {
			return self::returnError(self::$name . '1100', self::$errorArray[1100]);
		}
		//清除缓存
		self::clean();

		return self::returnSuccess(1);

	}

	////////////////////////////////////////////          编辑数据          ////////////////////////////////////////////

	/**
	 * 编辑分组用户
	 * @param int $id
	 * @param array $activityUserGroupId
	 * @param string $userUuid
	 * @param string $mobile
	 * @param string $nickname
	 * @return array
	 */
	static function update($id = 0, $activityUserGroupId = array(), $userUuid = '', $mobile = '', $nickname = '')
	{
		if (empty($id)) {
			return self::returnError(self::$name . '1002', self::$errorArray[1002]);
		}
		if (empty($mobile)) {
			return self::returnError(self::$name . '1001', self::$errorArray[1001]);
		}
		$params = array(
			'mobile' => $mobile,
		);
		$result = ManageActivityGroupUser::update(self::getConnection(), $id, $params);
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
	 * 分组用户删除
	 * @param int $id
	 * @return array
	 */
	static function delete($id = 0)
	{
		if (empty($id)) {
			return self::returnError(self::$name . '1002', self::$errorArray[1002]);
		}
		$result = ManageActivityGroupUser::delete(self::getConnection(), $id);
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

	////////////////////////////////////////////          业务数据          ////////////////////////////////////////////

	/**
	 * 用户加入分组
	 * @param array $mobiles
	 * @param int $activityUserGroupId
	 * @return array
	 */
	static function join($mobiles = array(), $activityUserGroupId = 1)
	{
		if (empty($activityUserGroupId)) {
			return self::returnError(self::$name . '1004', self::$errorArray[1004]);
		}
		if (empty($mobiles)) {
			return self::returnError(self::$name . '1001', self::$errorArray[1001]);
		}
		$successNum = 0;
		foreach ($mobiles as $mobile) {
			$resp = self::insert($activityUserGroupId, $mobile);
			if (self::checkInsertResult($resp) === false) {
				self::logError(__CLASS__ . '.' . __FUNCTION__, '用户加入分组失败：' . $mobile);
			} else {
				$successNum++;
			}
		}
		return self::returnSuccess($successNum);
	}

	/**
	 * 用户离开分组
	 * @param array $ids
	 * @return array
	 */
	static function leave($ids = array())
	{
		if (empty($ids)) {
			return self::returnError(self::$name . '1002', self::$errorArray[1002]);
		}
		$successNum = 0;
		foreach ($ids as $id) {
			$result = self::getByCache($id);
			if ($result['code'] !== 0) {
				self::logError(__CLASS__ . '.' . __FUNCTION__, '获取用户分组失败：' . $id);
				continue;
			}
			if ($result['data']['activityUserGroupId'] == 1) {
				self::logError(__CLASS__ . '.' . __FUNCTION__, '用户离开分组失败：' . self::$errorArray[1005]);
				continue;
			}
			$resp = self::delete($id);
			if (self::checkDeleteResultAndLeastOne($resp) === false) {
				self::logError(__CLASS__ . '.' . __FUNCTION__, '用户离开分组失败：' . $id);
			} else {
				$successNum++;
			}
		}
		return self::returnSuccess($successNum);
	}

	////////////////////////////////////////////          批量操作          ////////////////////////////////////////////

	/**
	 * 批量添加用户导出模板, 手机号
	 * @return array
	 */
	static function activityExcelUser($fileExcelName = '', $excelResult = false)
	{
		try {
			$fileName = '用户手机模板' . date('YmdHis');
			$columnName = array('手机号');
			$params = [];

			if ($excelResult === false) {
				$filePath = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $_ENV['PROJECT_apiDomainUpload'] . 'excel/result/' . $fileName . '.csv';
				$file = 'upload/excel/exceltask.txt';
				if (!is_file($file)) {
					mkdir('upload/excel');
					fopen($file, "w");
				}
				$content = file_get_contents($file);
				if (!empty($content)) {
					self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel/ 创建活动用户模板导出任务失败', 'jobExecutionExcel');
					return self::returnError(1000, '有任务正在执行中,请稍后再试');
				}
				$data = [
					'acticonName' => 'activityExcelUser',
					'fileName' => $fileName,
				];
				$reslut = file_put_contents($file, json_encode($data));
				if ($reslut) {
					self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel/ 创建活动用户模板导出任务成功', 'jobExecutionExcel');
					return self::returnSuccess([
						'path' => $filePath,
						'time' => 1
					]);
				} else {
					self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel/ 创建活动用户模板导出任务失败', 'jobExecutionExcel');
					return self::returnError(1000, '导出任务创建失败');
				}
			}
			if (!empty($fileExcelName)) {
				$fileName = $fileExcelName;
			}
			return self::excelData($columnName, $fileName, 1, 1, array(
				'className' => __CLASS__,
				'funcName' => 'activityExcelUserCall',
				'params' => $params
			));
		} catch (\Exception $e) {
			self::logInfo(__CLASS__ . __FUNCTION__, '上传文件失败' . $e->getMessage());
			return self::returnError(self::$name . '1000', $e->getMessage());
		}
	}

	/**
	 * 批量添加用户回调方法
	 * @param int $pageNumber
	 * @param int $PageSize
	 * @param int $params
	 * @return array
	 */
	static function activityExcelUserCall($pageNumber = 0, $PageSize = 0, $params = 0)
	{
		return [];
	}


	/**
	 * 导出用户数据
	 * @param int $activityGroupId
	 * @param string $keyword
	 * @param string $fileExcelName
	 * @param bool $excelResult
	 * @return array
	 */
	static function activityUserDataExcel($activityGroupId = 1, $keyword = '', $fileExcelName = '', $excelResult = false)
	{
		try {
			$fileName = '用户数据' . date('YmdHis');
			$columnName = array('昵称', '账号', '添加时间', '用户分组');
			$params = [
				'activityGroupId' => $activityGroupId,
				'keyword' => $keyword,
			];
			$count = ServiceAdminActivityGroup::getCountWithUsers($activityGroupId, $keyword);
			if ($count['code'] !== 0) {
				return $count;
			}
			$count = $count['data'];

			if ($excelResult === false) {
				$filePath = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $_ENV['PROJECT_apiDomainUpload'] . 'excel/result/' . $fileName . '.csv';
				$file = 'upload/excel/exceltask.txt';
				if (!is_file($file)) {
					mkdir('upload/excel');
					fopen($file, "w");
				}
				$content = file_get_contents($file);
				if (!empty($content)) {
					self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel/ 创建活动用户导出任务失败', 'jobExecutionExcel');
					return self::returnError(1000, '有任务正在执行中,请稍后再试');
				}
				$data = [
					'acticonName' => 'activityUserDataExcel',
					'activityGroupId' => $activityGroupId,
					'keyword' => $keyword,
					'fileName' => $fileName,
				];
				$reslut = file_put_contents($file, json_encode($data));
				if ($reslut) {
					self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel/ 创建活动用户导出任务成功', 'jobExecutionExcel');
					return self::returnSuccess([
						'path' => $filePath,
						'time' => ceil((int)$count / 1000) + 2
					]);
				} else {
					self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel/ 创建活动用户导出任务失败', 'jobExecutionExcel');
					return self::returnError(1001, '导出任务创建失败');
				}
			}
			if (!empty($fileExcelName)) {
				$fileName = $fileExcelName;
			}
			return self::excelData($columnName, $fileName, (int)$count, 100, array(
				'className' => __CLASS__,
				'funcName' => 'activityUserDataGetList',
				'params' => $params
			));
		} catch (\Exception $e) {
			self::logInfo(__CLASS__ . __FUNCTION__, '导出文件失败' . $e->getMessage());
			return self::returnError(self::$name . '1000', $e->getMessage());
		}
	}

	/**
	 * 批量添加用户回调方法
	 * @param array $params
	 * @return array
	 */
	static function activityUserDataGetList($params = array())
	{
		//调取ICE接口
		$dataList = ServiceAdminActivityGroup::getWithUsers($params['activityGroupId'], $params['keyword']);
		if ($dataList['code'] !== 0) {
			return $dataList;
		}

		$data = [];
		foreach ($dataList['data']['users'] as $key => $value) {
			$data[] = [
				'nickname' => $value['nickname'],
				'mobile' => $value['mobile'] . "\t",
				'createAt' => date('Y-m-d H:i:s', $value['createAt']),
				'groupList' => $value['groups'],
			];
		}

		return $data;
	}

	/**
	 * 用户数据转换 手机号转成用户UUid
	 * @param $fileName
	 * @return array
	 */
	static function activityExcelUserImport($fileName)
	{
		if (empty($_FILES[$fileName])) {
			return self::returnError('1001', self::$errorArray[1001]);
		}
		//获取数据
		$fileData = base64_encode(file_get_contents($_FILES[$fileName]['tmp_name']));
		$fileSuffix = substr($_FILES[$fileName]['name'], strrpos($_FILES[$fileName]['name'], '.') + 1);
		$filePath = ComponentFile::filePathWithData($fileData, $fileSuffix);
		// 数据检查
		if ($fileSuffix != 'csv') {
			return self::returnError('1002', self::$errorArray[1002]);
		}
		// 保存文件
		try {
			if (ComponentFile::saveFile($filePath, $fileData)) {
				return self::returnSuccess($filePath);
			} else {
				return self::returnError(self::$name . '1003', self::$errorArray[1003]);
			}
		} catch (\Exception $e) {
			return self::returnError(self::$name . '1000', $e->getMessage());
		}
	}

	/**
	 * 批量添加用户
	 * @param $file
	 * @param $activityUserGroupId
	 * @return array
	 */
	static function activityUserInsertMoreCheck($file, $activityUserGroupId)
	{
		set_time_limit(0);
		if (empty($file)) {
			return self::returnError(self::$name . '1002', self::$errorArray[1002]);
		}

		$fileNumber = count(file($file));
		$file = fopen($file, "r");
		for ($i = 0; $i < $fileNumber; $i++) {
			$fileData = fgetcsv($file);
			if ($i) {
				//中文处理
				$fileData[0] = iconv("GB2312", "UTF-8//IGNORE", $fileData[0]);
				//手机号格式不正确
				$isPhone = preg_match('/^1[34578]\d{9}$/', trim($fileData[0]));
				if (!$isPhone) {
					$mark = '手机号不符合';
					$data[] = [
						'mobile' => $fileData[0],
						'error' => 1,
						'mark' => $mark
					];
					continue;
				}
				$result = self::insert($activityUserGroupId, $fileData[0]);
				$data[] = [
					'mobile' => $fileData[0],
					'error' => $result['code'],
					'mark' => $result['code'] !== 0 ? $result['message'] : '',
				];
			}
		}
		fclose($file);
		return self::returnSuccess($data);
	}

	/**
	 * 添加分组用户
	 * @param $activityUserGroupId
	 * @param $mobile
	 * @return array
	 */
	static function activityUserInsertCheck($activityUserGroupId, $mobile)
	{
		$mark = '';
		//获取调取ICE接口参数配置
		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return self::returnError(self::$name . '1002', $token["message"]);
		}
		$token = $token["data"];
		$result = ComponentCzyEmployee::customerGetInfo($mobile, $token);
		if ($result['code'] !== 0) {
			$mark = "手机号不符合";
			return self::returnSuccess(['mobile' => $mobile, 'error' => 1, 'mark' => $mark,]);
		}
		//数据不存在, 或uuid不存在
		if (empty($result['data']) || empty($result['data']['uuid'])) {
			$mark = "手机号不符合";
			return self::returnSuccess(['mobile' => $mobile, 'error' => 1, 'mark' => $mark,]);
		}

		$activityUserGroupId = empty($activityUserGroupId) ? 1 : $activityUserGroupId;
		$group = ServiceAdminActivityGroup::get($activityUserGroupId);
		if ($group['code'] !== 0) {
			return self::returnError(self::$name . '1101', self::$errorArray[1101]);
		}
		$group = $group['data'];
		$data = [
			'mobile' => $mobile,
			'activity_user_group_id' => $activityUserGroupId,
			'activity_user_group_title' => $group['title'],
		];
		if (ManageActivityGroupUser::getCount(self::getConnection(), $data) > 0) {
			$mark = '该号码已存在';
			return self::returnSuccess(['mobile' => $mobile, 'error' => 1, 'mark' => $mark,]);
		}
		return self::returnSuccess([
			'mobile' => $mobile,
			'error' => 0,
			'mark' => $mark,
		]);
	}

	/**
	 * 批量添加用户
	 * @param $mobile
	 * @param $activityUserGroupId
	 * @return array
	 */
	static function activityUserInsertMore($mobile, $activityUserGroupId)
	{
		if (empty($mobile)) {
			return self::returnError(self::$name . '1015', self::$errorArray[1015]);
		}
		$number = count($mobile);
		$successNumber = 0;
		$errorNumber = 0;
		foreach ($mobile as $index => $item) {
			$result = self::insert($activityUserGroupId, $item);
			if ($result['code'] !== 0) {
				$errorNumber++;
			} else {
				$successNumber++;
			}
		}
		return self::returnSuccess('添加总数: ' . $number . ', 成功添加数量' . $successNumber . ', 失败数量' . $errorNumber);
	}


	/**
	 * 导出定时任务
	 */
	static function jobExecutionExcel()
	{
		BaseKernel::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel-start/ 导出任务开始', 'jobExecutionExcel');
		$file = dirname(dirname(__DIR__)) . '/public/upload/excel/exceltask.txt';
		if (!is_file($file)) {
			mkdir(dirname($file), 0755, true);
			fopen($file, "w");
		}
		$result = [];
		$task = file_get_contents($file);
		$task = json_decode($task, true);
		if (!empty($task)) {
			if ($task['acticonName'] == 'activityUserDataExcel') {
				//导出用户数据
				$result = self::activityUserDataExcel($task['activityGroupId'], $task['keyword'], $task['fileName'], true);
			}elseif ($task['acticonName'] == 'activityExcelUser') {
				//批量添加用户导出模板, 手机号
				$result = self::activityExcelUser($task['fileName'], true);
			}
		} else {
			BaseKernel::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel/ 没有订单/售后导出任务', 'jobExecutionExcel');
		}
		if (isset($result['code']) && $result['code'] === 0) {
			BaseKernel::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel/ 订单/售后导出成功' . json_encode($result), 'jobExecutionExcel');
		}

		file_put_contents($file, '');
		BaseKernel::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel-end/ 导出任务结束', 'jobExecutionExcel');
	}


	/**
	 * 清理定时任务
	 */
	static function jobExecutionExcelClean()
	{
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel-start/ 导出任务开始', 'jobExecutionExcel');
		$file = dirname(dirname(__DIR__)) . '/public/upload/excel/exceltask.txt';
		if (!is_file($file)) {
			mkdir(dirname($file), 0755, true);
			fopen($file, "w");
		}
		file_put_contents($file, '');
	}
}