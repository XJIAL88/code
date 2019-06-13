<?php

namespace App\Http\Controllers;

use App\Bases\BaseControllerAdmin;
use App\Services\ServiceAdminActivityAward;
use App\Services\ServiceAdminActivityAwardAward;

class ControllerAdminActivityAward extends BaseControllerAdmin
{
	/**
	 * 活动奖品
	 */
	/**
	 * 获取奖品
	 * http://localhost/admin/activityAwardGet?id=''（*奖品id）
	 */
	public function activityAwardGet()
	{
		$this->echoReturn(ServiceAdminActivityAward::activityAwardGet(
			$this->request('id', 0)
		));
	}
	/**
	 * 获取奖品列表
	 * http://localhost/admin/activityAwardGetlist?search=''（搜索关键词）&pageSize=''（每页条数）&pageNumber=''（页码）
	 */
	public function activityAwardGetlist()
	{
		$this->echoReturn(ServiceAdminActivityAward::activityAwardGetlist(
			$this->request('search', ''),
			$this->request('pageSize', 20),
			$this->request('pageNumber', 1)
		));
	}
	/**
	 * 添加奖品
	 * http://localhost/admin/activityAwardInsert?activityId=''（*活动Id）&activityName=''（*活动名称）&categoryId=''（*活动资源类型id）&categoryName=''（*活动资源类型名称）&resourceId=''（*活动资源id）&resourceName=''（*资源名称）&name=''（*奖品名称）&image=''（*奖品图片）&resourceNumber=''（*单个奖品需要的资源数量）&awardNumber=''（*奖品数量/金额）
	 */
	public function activityAwardInsert()
	{
		$this->echoReturn(ServiceAdminActivityAward::activityAwardInsert(
			$this->request('activityId', 0),
			$this->request('activityName', ''),
			$this->request('categoryId', 0),
			$this->request('categoryName', ''),
			$this->request('resourceId', 0),
			$this->request('resourceName', ''),
			$this->request('name', ''),
			$this->request('image', ''),
			$this->request('resourceNumber', 0.00),
			$this->request('awardNumber', 0)
		));
	}

	/**
	 * 编辑奖品
	 * http://localhost/admin/activityAwardUpdate?id=''（*奖品id）name=''（*奖品名称）&image=''（*奖品图片）&resourceNumber=''（*单个奖品需要的资源数量）&awardNumber=''（*奖品数量/金额）
	 */
	public function activityAwardUpdate()
	{
		$this->echoReturn(ServiceAdminActivityAward::activityAwardUpdate(
			$this->request('id', ''),
			$this->request('name', ''),
			$this->request('image', ''),
			$this->request('resourceNumber', 0),
			$this->request('awardNumber', 0)
		));
	}

	/**
	 * 启用
	 * http://localhost/admin/activityAwardShelf?id=''（*奖品id）
	 */
	public function activityAwardShelf()
	{
		$this->echoReturn(ServiceAdminActivityAward::activityAwardChangeStatus(
			$this->request('id', 0),
			1
		));
	}
	/**
	 * 禁用
	 * http://localhost/admin/activityAwardObtained?id=''（*奖品id）
	 */
	public function activityAwardObtained()
	{
		$this->echoReturn(ServiceAdminActivityAward::activityAwardChangeStatus(
			$this->request('id', 0),
			2
		));
	}

}
