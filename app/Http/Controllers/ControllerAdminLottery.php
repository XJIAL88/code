<?php

namespace App\Http\Controllers;

use App\Bases\BaseControllerAdmin;
use App\Services\ServiceAdminLottery;


class ControllerAdminLottery extends BaseControllerAdmin
{
	/**
	 * 抽奖
	 */
	/**
	 * 获取抽奖记录列表
	 * http://localhost/admin/lotteryParticipateRecordGetList?search=0（搜索词）&status=0（状态1：未领取2：已失效3：已领取）&startAt=0（开始时间）&endAt=0（结束时间）&pageSize=20（每页显示数量）&pageNumber=20（页码）
	 */
	public function lotteryParticipateRecordGetList()
	{
		$this->echoReturn(ServiceAdminLottery::lotteryParticipateRecordGetList(
			$this->request('search', ''),
			$this->request('status', 0),
			$this->request('startAt', 0),
			$this->request('endAt', 0),
			$this->request('pageSize', 20),
			$this->request('pageNumber', 1)
		));
	}

	/**
	 * 导出抽奖记录数据
	 * http://localhost/admin/lotteryRecordDataExcel?search=0（搜索词）&status=0（状态1：未领取2：已失效3：已领取）&startAt=0（开始时间）&endAt=0（结束时间）
	 */
	public function lotteryRecordDataExcel()
	{
		$this->echoReturn(ServiceAdminLottery::lotteryRecordDataExcel(
			$this->request('search', ''),
			$this->request('status', 0),
			$this->request('startAt', 0),
			$this->request('endAt', 0)
		));
	}

	/**
	 * 清理定时任务
	 * http://localhost/admin/jobExecutionExcelClean
	 */
	public function jobExecutionExcelClean()
	{
		$this->echoReturn(ServiceAdminLottery::jobExecutionExcelClean(
		));
	}

}
