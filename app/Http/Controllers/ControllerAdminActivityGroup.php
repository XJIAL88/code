<?php

namespace App\Http\Controllers;

use App\Bases\BaseControllerAdmin;
use App\Services\ServiceAdminActivityGroup;

class ControllerAdminActivityGroup extends BaseControllerAdmin
{

	/**
	 * 分组管理
	 */

	/**
	 * 获取分组
	 * http://localhost/admin/activityGroupGet?id=1（*分组ID）
	 */
	public function activityGroupGet()
	{
		$this->echoReturn(ServiceAdminActivityGroup::getByCache(
			$this->request('id', 0)
		));
	}

	/**
	 * 获取分组（含分组下用户及用户所属分组）
	 * http://localhost/admin/activityGroupGetWithUsers?id=1（*分组ID）&keyword=（*手机号或昵称）
	 */
	public function activityGroupGetWithUsers()
	{
		$this->echoReturn(ServiceAdminActivityGroup::getWithUsers(
			$this->request('id', 0),
			$this->request('keyword', '')
		));
	}

	/**
	 * 获取分组列表
	 * http://localhost/admin/activityGroupGetList?title=（标题）&pageSize=20（页数）&pageNumber（第几页，从1开始）
	 */
	public function activityGroupGetList()
	{
		$this->echoReturn(ServiceAdminActivityGroup::getListByCache(
			$this->request('title'),
			$this->request('pageSize', 0),
			$this->request('pageNumber', 1)
		));
	}

	/**
	 * 清理缓存
	 * http://localhost/admin/activityGroupClean
	 */
	public function activityGroupClean()
	{
		$this->echoReturn(ServiceAdminActivityGroup::clean());
	}

	/**
	 * 新建分组
	 * http://localhost/admin/activityGroupInsert?title=（*标题）
	 */
	public function activityGroupInsert()
	{
		$this->echoReturn(ServiceAdminActivityGroup::insert(
			$this->request('title')
		));
	}

	/**
	 * 更新分组
	 * http://localhost/admin/activityGroupUpdate?id=1（*分组ID）&title=（*标题）
	 */
	public function activityGroupUpdate()
	{
		$this->echoReturn(ServiceAdminActivityGroup::update(
			$this->request('id', 0),
			$this->request('title')
		));
	}

	/**
	 * 删除分组
	 * http://localhost/admin/activityGroupDelete?id=1（*分组ID）
	 */
	public function activityGroupDelete()
	{
		$this->echoReturn(ServiceAdminActivityGroup::delete(
			$this->request('id', 0)
		));
	}
}
