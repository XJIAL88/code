<?php

namespace App\Http\Controllers;

use App\Bases\BaseControllerAdmin;
use App\Services\ServiceAdminRole;

class ControllerAdminRole extends BaseControllerAdmin
{

	/**
	 * 角色管理
	 */

	/**
	 * 获取角色
	 * http://localhost/admin/roleGet?roleUuid=（角色UUID）
	 */
	public function roleGet()
	{
		$this->echoReturn(ServiceAdminRole::roleGet(
			$this->request('roleUuid', '')
		));
	}

	/**
	 * 获取角色列表
	 * http://localhost/admin/roleGetList?title=（标题）&super=0（类型1：管理员2：超级管理员）&startAt=0（开始时间）&endAt=0（结束时间）&pageSize=20（页数）&pageNumber=1（第几页，从1开始）&otherTraderId=（商户id）
	 */
	public function roleGetList()
	{

		$this->echoReturn(ServiceAdminRole::roleGetList(
			$this->request('title', ''),
			$this->request('uuid', ''),
			$this->request('super', ''),
			$this->request('startAt', 0),
			$this->request('endAt', 0),
			$this->request('pageSize', 10),
			$this->request('pageNumber', 1),
			$this->request('otherTraderId', 0)
		));
	}

	/**
	 * 添加角色
	 * http://localhost/admin/roleInsert?title=超级管理员（标题）&power=[]（权限【数组格式】）&super=2（类型1：管理员2：超级管理员）
	 */
	public function roleInsert()
	{
		$this->echoReturn(ServiceAdminRole::roleInsert(
			$this->request('title', ''),
			$this->request('power', array()),
			$this->request('super', 1)
		));
	}

	/**
	 * 查看角色权限
	 */
	public function roleGetPowerList()
	{
		$this->echoReturn(ServiceAdminRole::roleGetPowerList(
			$this->request('roleUuid')
		));
	}

	/**
	 * 编辑角色
	 * http://localhost/admin/roleUpdate?roleUuid=（角色UUID）&title=超级管理员2号（标题）&power=[]（权限【数组格式】）&super=2（类型1：管理员2：超级管理员）
	 */
	public function roleUpdate()
	{
		$this->echoReturn(ServiceAdminRole::roleUpdate(
			$this->request('roleUuid', ''),
			$this->request('title', ''),
			$this->request('super', 0),
			$this->request('power', array())
		));
	}

	/**
	 * 删除角色
	 * http://localhost/admin/roleDelete?roleUuid=（角色UUID）
	 */
	public function roleDelete()
	{
		$this->echoReturn(ServiceAdminRole::roleDelete(
			$this->request('roleUuid')
		));
	}
	/**
	 * 查看角色详情
	 * http://localhost/admin/roleDetail?roleName=（角色名称）
	 */
	public function roleDetail()
	{
		$this->echoReturn(ServiceAdminRole::roleDetail(
			$this->request('roleName')
		));
	}
	/**
	 * 查看角色权限
	 * http://localhost/admin/roleGetUserList?roleUuid=（角色UUID）
	 */
	public function roleGetUserList()
	{
		$this->echoReturn(ServiceAdminRole::roleGetUserList(
			$this->request('roleUuid','')
		));
	}

}

