<?php

namespace App\Http\Controllers;

use App\Bases\BaseControllerAdmin;
use App\Services\ServiceAdminActivity;

class ControllerAdminActivity extends BaseControllerAdmin
{
	/**
	 * 获取活动信息
	 * http://localhost/admin/activityGet?id=''（*活动id）
	 */
	public function activityGet()
	{
		$this->echoReturn(ServiceAdminActivity::activityGet(
			$this->request('id', 0)
		));
	}
	/**
	 * 获取活动列表
	 * http://localhost/admin/activityGetlist?name=''（活动名称）&startAt=''（活动开始时间）&endAt=''（活动结束时间）&groupId=''（活动范围id）&new=''（是否新人专享）&status=[]（活动状态1审批中2待配置3即将开始4进行中5已下架6已失效）&search=''（搜索关键字）
	 */
	public function activityGetlist()
	{
		$this->echoReturn(ServiceAdminActivity::activityGetlist(
			$this->request('name', ''),
			$this->request('number', ''),
			$this->request('startAt', 0),
			$this->request('endAt', 0),
			$this->request('groupId', ''),
			$this->request('new', 0),
			$this->request('status', []),
			$this->request('search', ''),
			$this->request('pageSize', 20),
			$this->request('pageNumber', 1)
		));
	}
	/**
	 * 添加活动
	 * http://localhost/admin/activityInsert?name=''（*活动名称）&type=''（*活动类型）&startAt=''（*活动开始时间）&endAt=''（*活动结束时间）&numberDaily=''（用户每日上限）&numberTotal=''（用户活动上限）&new=''（是否新人专享）&newDaylimit=''（新人天数设置）&rule=''（*规则）&desc=''（备注/描述）&resource=[]（*申请资源列表【[{"resourceId":0,"resourceName":"","categoryId":0,"category":"","grantType":1,"number":0}]】）
	 */
	public function activityInsert()
	{
		$this->echoReturn(ServiceAdminActivity::activityInsert(
			$this->adminUsername,
			$this->request('name', ''),
			$this->request('type', 1),
			$this->request('startAt', 0),
			$this->request('endAt', 0),
			$this->request('numberDaily', 0),
			$this->request('numberTotal', 0),
			$this->request('new', 0),
			$this->request('newDaylimit', 0),
			$this->request('rule', ''),
			$this->request('desc', ''),
			$this->request('resource', [])
		));
	}

	/**
	 * 编辑活动/奖项配置
	 * http://localhost/admin/activityUpdate?id=''（*活动id）&level=[]（*自动失效奖项等级）&time=[]（*["00:00-08:00","08:00-14:00","14:00-24:00"]活动时间段）&resourceArray=''（*奖项配置【[{"activity_id":"奖品id","award_id":"资源id","name":"奖项名称","level":"奖项等级","category_id":"","category":"奖品类型id","nature":"奖品性质1数量型2金额型","number":"奖品数量","personal_daily_number":"用户每日中奖数量上限","all_daily_number":"所有用户每日中奖数量上限","rate":[中奖概率21,22,23]}]】）
	 */
	public function activityUpdate()
	{
		$this->echoReturn(ServiceAdminActivity::activityUpdate(
			$this->adminUsername,
			$this->request('id', 0),
			$this->request('level', []),
			$this->request('time', []),
			$this->request('resourceArray', [])

		));
	}
	/**
	 * 删除活动
	 * http://localhost/admin/activityDelete?id=''（*活动id）
	 */
	public function activityDelete()
	{
		$this->echoReturn(ServiceAdminActivity::activityDelete(
			$this->request('id', 0)
		));
	}
	/**
	 * 上架
	 * http://localhost/admin/activityShelf?id=''（*活动id）
	 */
	public function activityShelf()
	{
		$this->echoReturn(ServiceAdminActivity::activityChangeStatus(
			$this->request('id', 0),
			1
		));
	}
	/**
	 * 下架
	 * http://localhost/admin/activityObtained?id=''（*活动id）
	 */
	public function activityObtained()
	{
		$this->echoReturn(ServiceAdminActivity::activityChangeStatus(
			$this->request('id', 0),
			2
		));
	}

	/**
	 * 释放资源
	 * http://localhost/admin/activityFreeResource?id=''（*活动id）
	 */
	public function activityFreeResource()
	{
		$this->echoReturn(ServiceAdminActivity::activityFreeResource(
			$this->request('id', 0)
		));
	}


	/**
	 * 活动资源
	 */

	/**
	 * 获取资源分类
	 * http://localhost/admin/resourceCategoryList?title=(类目名称)&status=(状态空全部1启用2禁用)&startTime=(创建开始时间)&endTime=(创建结束时间)&pageSize=20（每页数量）&pageNumber=1（第几页，从1开始）
	 */
	public function resourceCategoryList()
	{
		$this->echoReturn(ServiceAdminActivity::resourceCategoryList(
			$this->request('title', ''),
			$this->request('status', 1),
			$this->request('startTime', 0),
			$this->request('endTime', 0),
			$this->request('pageSize', 20),
			$this->request('pageNumber', 1)
		));
	}

	/**
	 * 获取资源列表
	 * http://localhost/admin/resourceGetList?category=注册（资源类目）&platform=京东(资源方) &name=注册新人5元劵(资源名称) &status=1 (资源状态：1可用 2禁用) &grantType=1（发放方式：1自动发放 2商家发货 3平台发货）&callType=1（调用方式） &pageSize=10（页数）&pageNumber=1（第几页，从1开始）
	 */
	public function resourceGetList()
	{
		$this->echoReturn(ServiceAdminActivity::resourceGetList(
			$this->request('category', ''),
			$this->request('categoryId', 0),
			$this->request('platform', ''),
			$this->request('name', ''),
			$this->request('status', 1),
			$this->request('grantType', 0),
			$this->request('callType', ''),
			$this->request('pageSize', 0),
			$this->request('pageNumber', 1)
		));
	}

	/**
	 * 获取活动的资源明细
	 * http://localhost/admin/activityResourceDetail?orderNumber=232323(*活动id)&search=''(资源名称或资源id)&categoryId=''(资源类型id)&pageSize=0(每页显示条数) &pageNumber=1(页码)
	 */
	public function activityResourceDetail()
	{
		$this->echoReturn(ServiceAdminActivity::activityResourceDetail(
			$this->request('orderNumber', ''),
			$this->request('search', ''),
			$this->request('categoryId', 0),
			$this->request('pageSize', 10),
			$this->request('pageNumber', 1)
		));
	}

//	/**
//	 * 抽奖测试
//	 */
//	/**
//	 * 申请抽奖资格
//	 * http://localhost/api/lotteryQualificationsApply?number=0（*活动ID）&mobile=0（*用户手机号）
//	 */
//	public function lotteryQualificationsApply()
//	{
//		$this->echoReturn(ServiceAdminActivity::lotteryQualificationsApply(
//			$this->request('number',''),
//			$this->request('mobile','')
//		));
//	}
//	/**
//	 * 抽奖
//	 * http://localhost/api/getLotteryResult?number=0（*活动ID）&mobile=0（*用户手机号）
//	 */
//	public function getLotteryResult()
//	{
//		$this->echoReturn(ServiceAdminActivity::getLotteryResult(
//			$this->request('number',''),
//			$this->request('mobile','')
//		));
//	}
//
//	/**
//	 * 申请资格&抽奖
//	 * http://localhost/api/qualificationsApplyAndGetLotteryResult?number=0（*活动ID）&mobile=0（*用户手机号）
//	 */
//	public function qualificationsApplyAndGetLotteryResult()
//	{
//		$this->echoReturn(ServiceAdminActivity::qualificationsApplyAndGetLotteryResult(
//			$this->request('number',''),
//			$this->request('mobile','')
//		));
//	}

}
