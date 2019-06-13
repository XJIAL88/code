<?php

namespace App\Bases;

use Illuminate\Support\Facades\DB;
use Monolog\Logger;

/**
 * 业务基类-API业务
 * User: Administrator
 * Date: 2016/7/28
 * Time: 16:06
 */
class BaseServiceApi extends BaseService
{
	static $baseErrorArray = array(
		'1' => '',
		'2' => '',
		'3' => '',
	);

	/**
	 * 信息日志
	 * @param $title
	 * @param $content
	 * @param string $file
	 */
	static function logInfo($title, $content, $file = 'lumen')
	{
		self::log('api', $title, $content, Logger::INFO, $file);
	}

	/**
	 * 错误日志
	 * @param $title
	 * @param $content
	 * @param string $file
	 */
	static function logError($title, $content, $file = 'lumen')
	{
		self::log('api', $title, $content, Logger::ERROR, $file);
	}

	/**
	 * 错误日志
	 * @param $title
	 * @param $content
	 * @param array $data
	 */
	static function logErrorData($title, $content, $data = array())
	{
		self::log('api', $title, $content . (empty($data) ? '' : '【' . json_encode($data) . '】'), Logger::ERROR, 'error');
	}

}