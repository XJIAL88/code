<?php

namespace App\Http\Controllers;

use App\Bases\BaseControllerAdmin;
use App\Services\ServiceAdminAdmin;

class ControllerAdminAdmin extends BaseControllerAdmin
{

	/**
	 * 账户管理
	 */

	/**
	 * 获取账户
	 * http://localhost/admin/adminGet?id=1（账户ID）
	 */
	public function adminGet()
	{
		$this->echoReturn(ServiceAdminAdmin::adminGetByCache(
			$this->request('id', 0)
		));
	}

	/**
	 * 获取账户列表
	 * http://localhost/admin/adminGetList?roleUuid=0（角色ID）&username=（用户名）&status=0（状态1：正常2：禁用）&startAt=0（开始时间）&endAt=0（结束时间）&pageSize=20（页数）&pageNumber（第几页，从1开始）
	 */
	public function adminGetList()
	{
		$this->echoReturn(ServiceAdminAdmin::adminGetListByCache(
			$this->request('roleUuid', 0),
			$this->request('username', ''),
			$this->request('status', 0),
			$this->request('startAt', 0),
			$this->request('endAt', 0),
			$this->request('pageSize', 0),
			$this->request('pageNumber', 1)
		));
	}

	/**
	 * 创建账户
	 * http://localhost/admin/adminInsert?roleUuid=（角色UUID）&username=czy（账户名）&status=1（状态1：正常2：禁用）
	 */
	public function adminInsert()
	{
		$this->echoReturn(ServiceAdminAdmin::adminInsert(
			$this->request('roleUuid', ''),
			$this->request('username', ''),
			$this->request('status', 0)
		));
	}

	/**
	 * 编辑账户
	 * http://localhost/admin/adminUpdate?id=2（账户ID）&roleUuid=2（角色UUID）&status=2（状态1：正常2：禁用）
	 */
	public function adminUpdate()
	{
		$this->echoReturn(ServiceAdminAdmin::adminUpdate(
			$this->request('id', 0),
			$this->request('roleUuid', ''),
			$this->request('status', 0)
		));
	}

	/**
	 * 绑定账户
	 * http://localhost/admin/adminBindTrader?mobile=133326598874（账户手机号）&roleUuid=（角色UUID）&chageTraderId=（要改变的商户id）
	 */
	public function adminBindTrader()
	{
		$this->echoReturn(ServiceAdminAdmin::adminBindTrader(
			$this->request('mobile', ''),
			$this->request('roleUuid', ''),
			$this->request('chageTraderId', '')
		));
	}

	/**
	 * 修改账户密码
	 * http://localhost/admin/adminUpdatePassword?password=1（原密码）&newPassword=122（新密码）
	 */
	public function adminUpdatePassword()
	{
		$this->echoReturn(ServiceAdminAdmin::adminUpdatePassword(
			$this->adminId,
			$this->request('password', ""),
			$this->request('newPassword', "")
		));
	}

	/**
	 * 删除账户
	 * http://localhost/admin/adminDelete?id=2（账户ID）
	 */
	public function adminDelete()
	{
		$this->echoReturn(ServiceAdminAdmin::adminDelete(
			$this->request('id', 0)
		));
	}
	/**
	 * 账户权限列表
	 */
	public function adminGetPowerList()
	{
		$this->echoReturn(ServiceAdminAdmin::adminGetPowerList(
			$this->request('userUuid','')
		));
	}
	/**
	 * 获取审核商品列表
	 * http://localhost/admin/adminGetActionRecordList?operateAdminId=0（管理员ID）&adminName=（管理员名称）&number=（订单编号）&pageNumber=1（第几页，从1开始）&pageSize=20（页数）
	 */
	public function adminGetActionRecordList()
	{
		$this->echoReturn(ServiceAdminAdmin::adminGetActionRecordList(
			$this->request('operateAdminId', 0),
			$this->request('adminName', ''),
			$this->request('number', ''),
			$this->request('pageSize', 10),
			$this->request('pageNumber', 1)
		));
	}
}
