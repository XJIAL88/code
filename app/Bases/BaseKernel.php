<?php

namespace App\Bases;

use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use Monolog\Logger;

class BaseKernel extends ConsoleKernel
{
	/**
	 * 信息日志
	 * @param $title
	 * @param $content
	 * @param string $file
	 */
	static function logInfo($title, $content, $file = 'lumen')
	{
		BaseService::log('kernel', $title, $content, Logger::INFO, $file);
	}

	/**
	 * 错误日志
	 * @param $title
	 * @param $content
	 * @param string $file
	 */
	static function logError($title, $content, $file = 'lumen')
	{
		BaseService::log('kernel', $title, $content, Logger::ERROR, $file);
	}
}
