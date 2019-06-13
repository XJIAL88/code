<?php

namespace App\Http\Controllers;

use App\Bases\BaseControllerAdmin;
use App\Services\ServiceAdminPower;

class ControllerAdminPower extends BaseControllerAdmin
{

	/**
	 * 权限管理
	 */

	/**
	 * 获取权限
	 * http://localhost/admin/powerGet?privilegeUuid=1（权限UUID）
	 */
	public function powerGet()
	{
		$this->echoReturn(ServiceAdminPower::powerGetByCache(
			$this->request('privilegeUuid', '')
		));
	}

	/**
	 * 获取权限列表
	 * http://localhost/admin/powerGetList?type=1（类型1：运营平台2：商户平台）&parentUuid=（父级uuid）&name=（接口名称/路由）&desc=（接口描述）&startAt=0（开始时间）&endAt=0（结束时间）&pageSize=20（页数）&pageNumber=1（第几页，从1开始）
	 */
	public function powerGetList()
	{
		$this->echoReturn(ServiceAdminPower::powerGetListByCache(
			$this->request('type', 0),
			$this->request('parentUuid',''),
			$this->request('name', ''),
			$this->request('desc', ''),
			$this->request('startAt', 0),
			$this->request('endAt', 0),
			$this->request('pageSize', 10),
			$this->request('pageNumber', 1)
		));
	}
	/**
	 * 添加接口权限
	 * http://localhost/admin/powerInsert?type=1（类型1：运营平台2：商户平台）&parentUuid=（父级uuid）&name=powerGet（接口名称）&desc=获取权限（接口描述）
	 */
	public function powerInsert()
	{
		$this->echoReturn(ServiceAdminPower::powerInsert(
			$this->request('type', 0),
			$this->request('parentUuid', ''),
			$this->request('name', '#'),
			$this->request('desc', '')
		));
	}

	/**
	 * 编辑接口权限
	 * http://localhost/admin/powerUpdate?privilegeUuid=1（接口权限uuid）&parentUuid=（父级权限uuid）&type=1（类型1：运营平台2：商户平台）&name=powerGetList（接口名称）&desc=获取权限列表（接口描述）
	 */
	public function powerUpdate()
	{
		$this->echoReturn(ServiceAdminPower::powerUpdate(
			$this->request('privilegeUuid', ''),
			$this->request('parentUuid', ''),
			$this->request('name', ''),
			$this->request('desc', '')
		));
	}

	/**
	 * 删除接口权限
	 * http://localhost/admin/powerDelete?privilegeUuid=1（权限uuid）
	 */
	public function powerDelete()
	{
		$this->echoReturn(ServiceAdminPower::powerDelete(
			$this->request('privilegeUuid','')
		));
	}
}
