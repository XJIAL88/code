<?php

namespace App\Http\Controllers;

use App\Bases\BaseControllerAdmin;
use App\Services\ServiceAdminActivityGroupUser;

class ControllerAdminActivityGroupUser extends BaseControllerAdmin
{

	/**
	 * 分组用户管理
	 */

	/**
	 * 获取分组用户
	 * http://localhost/admin/activityGroupUserGet?id=1（分组用户ID）
	 */
	public function activityGroupUserGet()
	{
		$this->echoReturn(ServiceAdminActivityGroupUser::getByCache(
			$this->request('id', 0)
		));
	}

	/**
	 * 获取分组用户列表
	 * http://localhost/admin/activityGroupUserGetList?activityUserGroupId=0（用户分组id）&userUuid=（用户uuid）&mobile=（用户手机号）&nickname=（用户昵称）&pageSize=20（页数）&pageNumber（第几页，从1开始）
	 */
	public function activityGroupUserGetList()
	{
		$this->echoReturn(ServiceAdminActivityGroupUser::getListByCache(
			$this->request('activityUserGroupId'),
			$this->request('userUuid'),
			$this->request('mobile'),
			$this->request('nickname'),
			$this->request('pageSize', 0),
			$this->request('pageNumber', 1)
		));
	}

	/**
	 * 清理缓存
	 * http://localhost/admin/activityGroupUserClean
	 */
	public function activityGroupUserClean()
	{
		$this->echoReturn(ServiceAdminActivityGroupUser::clean());
	}

	/**
	 * 新建分组用户
	 * http://localhost/admin/activityGroupUserInsert?activityUserGroupId=0（用户分组id）&userUuid=（用户uuid）&mobile=（*用户手机号）&nickname=（用户昵称）
	 */
	public function activityGroupUserInsert()
	{
		$this->echoReturn(ServiceAdminActivityGroupUser::insert(
			$this->request('activityUserGroupId'),
			$this->request('mobile')
		));
	}

	/**
	 * 更新分组用户
	 * http://localhost/admin/activityGroupUserUpdate?id=1（分组用户ID）&activityUserGroupId=（【数组，格式：[0,1]】用户分组id）&userUuid=（用户uuid）&mobile=（*用户手机号）&nickname=（用户昵称）
	 */
	public function activityGroupUserUpdate()
	{
		$this->echoReturn(ServiceAdminActivityGroupUser::update(
			$this->request('id', 0),
			$this->request('activityUserGroupId', array()),
			$this->request('userUuid'),
			$this->request('mobile'),
			$this->request('nickname')
		));
	}

	/**
	 * 删除分组用户
	 * http://localhost/admin/activityGroupUserDelete?id=1（分组用户ID）
	 */
	public function activityGroupUserDelete()
	{
		$this->echoReturn(ServiceAdminActivityGroupUser::delete(
			$this->request('id', 0)
		));
	}

	/**
	 * 用户加入分组
	 * http://localhost/admin/activityGroupUserJoin?mobile=（【数组，格式：[1300000000,1300000000]】用户手机号）&activityUserGroupId=0（用户分组id）
	 */
	public function activityGroupUserJoin()
	{
		$this->echoReturn(ServiceAdminActivityGroupUser::join(
			$this->request('mobile', array()),
			$this->request('activityUserGroupId')
		));
	}

	/**
	 * 用户离开分组
	 * http://localhost/admin/activityGroupUserLeave?id=1（【数组，格式：[1,2]】分组用户ID）
	 */
	public function activityGroupUserLeave()
	{
		$this->echoReturn(ServiceAdminActivityGroupUser::leave(
			$this->request('id', array())
		));
	}

	/**
	 * 导出批量添加用户模板
	 * http://localhost/admin/activityExcelUser
	 */
	public function activityExcelUser()
	{
		$this->echoReturn(ServiceAdminActivityGroupUser::activityExcelUser(
		));
	}

	/**
	 * 导出用户数据
	 * http://localhost/admin/activityUserDataExcel?activityUserGroupId=（分组id）&keyword=（搜索关键词）
	 */
	public function activityUserDataExcel()
	{
		$this->echoReturn(ServiceAdminActivityGroupUser::activityUserDataExcel(
			$this->request('activityUserGroupId',''),
			$this->request('keyword','')
		));
	}

	/**
	 * 导入批量添加用户数据
	 * http://localhost/admin/activityExcelUserImport?fileName=file（*表单上传文件名）
	 */
	public function activityExcelUserImport()
	{
		$this->echoReturn(ServiceAdminActivityGroupUser::activityExcelUserImport(
			$this->request('fileName', "file")
		));
	}

	/**
	 * 批量添加用户数据检测
	 * http://localhost/admin/activityUserInsertMoreCheck?file=file（*上传文件路径）&activityUserGroupUuid=（*分组id）
	 */
	public function activityUserInsertMoreCheck()
	{
		$this->echoReturn(ServiceAdminActivityGroupUser::activityUserInsertMoreCheck(
			$this->request('file',''),
			$this->request('activityUserGroupId',1)
		));
	}

	/**
	 * 检验用户是否可以被添加
	 * http://localhost/admin/activityUserInsertCheck?activityUserGroupId=（*分组的Id）&mobile=（*手机号）
	 */
	public function activityUserInsertCheck()
	{
		$this->echoReturn(ServiceAdminActivityGroupUser::activityUserInsertCheck(
			$this->request('activityUserGroupId',1),
			$this->request('mobile','')
		));
	}

	/**
	 * 批量添加用户
	 * http://localhost/admin/activityUserInsertMore?mobile=[]（*【用户手机号】）&activityUserGroupId=（*分组id）
	 */
	public function activityUserInsertMore()
	{
		$this->echoReturn(ServiceAdminActivityGroupUser::activityUserInsertMore(
			$this->request('mobile',[]),
			$this->request('activityUserGroupId','')
		));
	}

	/**
	 * 清理定时任务
	 * http://localhost/admin/jobExecutionExcelClean
	 */
	public function jobExecutionExcelClean()
	{
		$this->echoReturn(ServiceAdminActivityGroupUser::jobExecutionExcelClean(
		));
	}
}
