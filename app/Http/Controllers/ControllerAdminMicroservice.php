<?php

namespace App\Http\Controllers;

use App\Bases\BaseControllerMicroApi;
use App\Services\ServiceAdminMicroservice;

class ControllerAdminMicroservice extends BaseControllerMicroApi
{
    /**
     * 活动抽奖微服务
     */

	/**
	 * 申请抽奖资格
	 * http://localhost/api/lotteryQualificationsApply?number=0（*活动ID）&mobile=0（*用户手机号）
	 */
	public function lotteryQualificationsApply()
	{
		$this->echoReturn(ServiceAdminMicroservice::lotteryQualificationsApply(
			$this->request('number',''),
			$this->request('mobile','')
		));
	}

	/**
	 * 抽奖
	 * http://localhost/api/getLotteryResult?number=0（*活动ID）&mobile=0（*用户手机号）
	 */
	public function getLotteryResult()
	{
		$this->echoReturn(ServiceAdminMicroservice::getLotteryResult(
			$this->request('number',''),
			$this->request('mobile','')
		));
	}

	/**
	 * 申请资格&抽奖
	 * http://localhost/api/qualificationsApplyAndGetLotteryResult?number=0（*活动ID）&mobile=0（*用户手机号）
	 */
	public function qualificationsApplyAndGetLotteryResult()
	{
		$this->echoReturn(ServiceAdminMicroservice::qualificationsApplyAndGetLotteryResult(
			$this->request('number',''),
			$this->request('mobile','')
		));
	}

	/**
	 * 获取抽奖记录列表
	 * http://localhost/api/getLotteryList?number=0（*活动ID）&userId=0（*用户id）&award=0（*是否中奖1是2否）&pageSize=0（*页面大小）&pageNumber=0（*页码）
	 */
	public function getLotteryList()
	{
		$this->echoReturn(ServiceAdminMicroservice::getLotteryList(
			$this->request('number',''),
			$this->request('userId',0),
			$this->request('award',0),
			$this->request('pageSize',10),
			$this->request('pageNumber',1)
		));
	}

	/**
	 * 获取抽奖记录明细
	 * http://localhost/api/getLotteryDetail?id=0（*抽奖记录id）
	 */
	public function getLotteryDetail()
	{
		$this->echoReturn(ServiceAdminMicroservice::getLotteryDetail(
			$this->request('id',0)
		));
	}

	/**
	 * 获取抽奖入口地址
	 * http://localhost/api/getLotteryUrl
	 */
	public function getLotteryUrl()
	{
		$this->echoReturn(ServiceAdminMicroservice::getLotteryUrl(
		));
	}

}

