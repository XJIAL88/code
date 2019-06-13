<?php

namespace App\Services;

use App\Bases\BaseKernel;
use App\Bases\BaseServiceAdmin;
use App\Manages\ManageLotteryAward;
use App\Manages\ManageLotteryDebrisRecord;
use App\Manages\ManageLotteryRecord;
use App\Manages\ManageLotteryShareRecord;
use App\Manages\ManageLotteryUserDebris;
use App\Manages\ManageLotteryUserExchange;
use App\Manages\ManageUser;
use App\Component\ComponentVariable;
use App\Component\ComponentCzy;
use Illuminate\Support\Facades\DB;

/**
 * 业务 抽奖
 * User: Administrator
 * Date: 2016/7/28
 * Time: 16:06
 */
class ServiceAdminLottery extends BaseServiceAdmin
{
	static $errorArray = array();


	/**
	 * 历史记录
	 * @param int $search
	 * @param int $status
	 * @param int $startAt
	 * @param int $endAt
	 * @param int $pageSize
	 * @param int $pageNumber
	 * @return array
	 */
	static function lotteryParticipateRecordGetList($search = '', $status = 0, $startAt = 0, $endAt = 0, $pageSize = 20, $pageNumber = 1)
	{
		$count = ManageLotteryRecord::getCountBySearch(self::getConnection(), $search, $status, $startAt, $endAt);
		$orders = ['create_at' => 'desc'];
		$list = ManageLotteryRecord::getListBySearch(self::getConnection(), $search, $status, $startAt, $endAt, $orders, $pageSize, $pageNumber);

		return self::returnSuccess([
			'dataCount' => $count,
			'dataList' => $list,
		]);
	}

	/**
	 * 导出抽奖记录数据
	 * @param string $search
	 * @param int $status
	 * @param int $startAt
	 * @param int $endAt
	 * @return array|string
	 */
	static function lotteryRecordDataExcel($search = '', $status = 0, $startAt = 0, $endAt = 0, $fileExcelName = '', $excelResult = false)
	{
		try {
			$fileName = '抽奖记录列表' . date('YmdHis');
			$columnName = array('ID', '抽奖时间', '活动ID', '来源活动', '用户名称', '手机号码', '奖品名称', '领取状态');
			$params = [
				'search' => $search,
				'status' => $status,
				'startAt' => $startAt,
				'endAt' => $endAt,
			];
			$count = ManageLotteryRecord::getCountBySearch(self::getConnection(), $search, $status, $startAt, $endAt);

			if ($excelResult === false) {
				$filePath = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $_ENV['PROJECT_apiDomainUpload'] . 'excel/result/' . $fileName . '.csv';
				$file = 'upload/excel/exceltask.txt';
				if (!is_file($file)) {
					mkdir('upload/excel');
					fopen($file, "w");
				}
				$content = file_get_contents($file);
				if (!empty($content)) {
					self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel/ 创建抽奖记录数据导出任务失败', 'jobExecutionExcel');
					return self::returnError(1000, '有任务正在执行中,请稍后再试');
				}
				$data = [
					'acticonName' => 'lotteryRecordDataExcel',
					'params' => $params,
					'fileName' => $fileName,
				];
				$reslut = file_put_contents($file, json_encode($data));
				if ($reslut) {
					self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel/ 创建抽奖记录数据导出任务成功', 'jobExecutionExcel');
					return self::returnSuccess([
						'path' => $filePath,
						'time' => ceil((int)$count / 1000) + 2
					]);
				} else {
					self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel/ 创建抽奖记录数据导出任务失败', 'jobExecutionExcel');
					return self::returnError(1001, '导出任务创建失败');
				}
			}
			if (!empty($fileExcelName)) {
				$fileName = $fileExcelName;
			}
			return self::excelData($columnName, $fileName, (int)$count, 100, array(
				'className' => __CLASS__,
				'funcName' => 'lotteryRecordDataGetList',
				'params' => $params
			));
		} catch (\Exception $e) {
			self::logInfo(__CLASS__ . __FUNCTION__, '导出文件失败' . $e->getMessage());
			return self::returnError('100011', $e->getMessage());
		}
	}

	/**
	 * 批量添加用户回调方法
	 * @param int $pageNumber
	 * @param int $pageSize
	 * @param array $params
	 * @return array
	 */
	static function lotteryRecordDataGetList($pageNumber = 0, $pageSize = 0, $params = [])
	{
		$orders = ['create_at' => 'desc'];
		$list = self::arObjects2Array(ManageLotteryRecord::getListBySearch(self::getConnection(), $params['search'], $params['status'], $params['startAt'], $params['endAt'], $orders, $pageSize, $pageNumber));

		$data = [];
		$status = [
			'1' => '未领取',
			'2' => '已失效',
			'3' => '已领取领取',
		];
		foreach ($list as $key => $value) {
			$data[] = [
				'id' => $value['id'],
				'create_at' => date('Y-m-d H:i:s', $value['createAt']) . "\t",
				'activity_number' => $value['activityNumber'] . "\t",
				'activity_name' => $value['activityName'],
				'user_name' => $value['userName'],
				'user_mobile' => $value['userMobile'] . "\t",
				'award_name' => $value['awardName'],
				'status' => $status[$value['status']],
			];
		}

		return $data;
	}


	/**
	 * 导出定时任务
	 */
	static function jobExecutionExcel()
	{
		BaseKernel::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel-start/ 导出任务开始', 'jobExecutionActivity');
		$file = dirname(dirname(__DIR__)) . '/public/upload/excel/exceltask.txt';
		if (!is_file($file)) {
			mkdir(dirname($file), 0755, true);
			fopen($file, "w");
		}
		$result = [];
		$task = file_get_contents($file);
		$task = json_decode($task, true);
		$params = $task['params'];
		if (!empty($task)) {
			if ($task['acticonName'] == 'lotteryRecordDataExcel') {
				//导出用户数据
				$result = self::lotteryRecordDataExcel($params['search'], $params['status'], $params['startAt'], $params['endAt'], $task['fileName'],true);
			}
		} else {
			BaseKernel::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel/ 没有抽奖历史记录导出任务', 'jobExecutionActivity');
		}
		if (isset($result['code']) && $result['code'] === 0) {
			BaseKernel::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel/ 抽奖历史记录导出成功' . json_encode($result), 'jobExecutionActivity');
		}
		file_put_contents($file, '');
		BaseKernel::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel-end/ 导出任务结束', 'jobExecutionActivity');
	}


	/**
	 * 清理定时任务
	 */
	static function jobExecutionExcelClean()
	{
		$file = dirname(dirname(__DIR__)) . '/public/upload/excel/exceltask.txt';
		if (!is_file($file)) {
			mkdir(dirname($file), 0755, true);
			fopen($file, "w");
		}
		file_put_contents($file, '');
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel-start/ 导出任务已清除', 'jobExecutionActivity');
	}

}