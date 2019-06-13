<?php

namespace App\Bases;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Base
{
	/**
	 * 日志
	 * @param $name
	 * @param $title
	 * @param $content
	 * @param int $level
	 * @param string $file
	 */
	static function log($name, $title, $content, $level = Logger::INFO, $file = 'lumen')
	{
		try {
			$log = new Logger($name);
			$log->pushHandler(new StreamHandler(storage_path('logs/' . date('Ym/Ymd') . '.' . $name . '.' . $file . '.log'), 0));
			if ($level === Logger::INFO) {
				$log->addInfo($title . '：' . $content);
			} elseif ($level === Logger::ERROR) {
				$log->addError($title . '：' . $content);
			}
		} catch (\Exception $e) {
		}
	}
}