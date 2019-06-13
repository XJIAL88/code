<?php

namespace App\Console;

use App\Bases\BaseKernel;
use App\Services\ServiceAdminActivity;
use App\Services\ServiceAdminActivityGroupUser;
use App\Services\ServiceAdminJobs;
use App\Services\ServiceAdminLottery;
use Illuminate\Console\Scheduling\Schedule;

class Kernel extends BaseKernel
{
	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		//
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule $schedule
	 * @return void
	 * @throws \Exception
	 */
	protected function schedule(Schedule $schedule)
	{
		// 检查是否有订单导出任务
//		$schedule->call(function () {
//			ServiceAdminActivityGroupUser::jobExecutionExcel();
//		})->everyMinute()->name('jobExecutionExcel')->withoutOverlapping();

		//检测是否有抽奖历史导出任务
		$schedule->call(function () {
			ServiceAdminLottery::jobExecutionExcel();
		})->everyMinute()->name('jobExecutionExcel')->withoutOverlapping();

		// 检查活动是否已结束
		$schedule->call(function () {
			ServiceAdminActivity::jobExecutionActivity();
		})->everyMinute()->name('jobExecutionExcel')->withoutOverlapping();

	}
}
