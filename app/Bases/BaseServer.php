<?php

namespace App\Bases;

use App\Component\ComponentString;
use Monolog\Logger;

/**
 * 子业务基类
 * Class BaseService
 * @package App\Services
 */
class BaseServer extends Base
{
	/**
	 * 信息日志
	 * @param $title
	 * @param $content
	 * @param string $file
	 */
	static function logInfo($title, $content, $file = 'lumen')
	{
		Base::log('server', $title, $content, Logger::INFO, $file);
	}

	/**
	 * 错误日志
	 * @param $title
	 * @param $content
	 * @param string $file
	 */
	static function logError($title, $content, $file = 'lumen')
	{
		Base::log('server', $title, $content, Logger::ERROR, $file);
	}

	/**
	 * AR对象转换为数组
	 * @param $arObject
	 * @return mixed
	 */
	static function arObject2Array($arObject)
	{
		if ($arObject && is_object($arObject)) {
			$result = array();
			foreach ($arObject as $key => $item) {
				$result[ComponentString::convertUnderline($key)] = $item;
			}
			return $result;
		}

		return null;
	}

	/**
	 * AR对象数组转换为二维数组
	 * @param $arObjects
	 * @return array
	 */
	static function arObjects2Array($arObjects)
	{
		$result = array();
		foreach ($arObjects as $item) {
			$result[] = self::arObject2Array($item);
		}
		return $result;
	}


	/**
	 * 检查添加结果
	 * @param $result
	 * @return bool 成功返回true，否则返回false
	 */
	static function checkInsertResult($result)
	{
		return is_numeric($result) && $result > 0;
	}

	/**
	 * 检查更新结果
	 * @param $result
	 * @return bool 成功返回true，否则返回false
	 */
	static function checkUpdateResult($result)
	{
		if (is_numeric($result)) {
			return true;
		}

		return false;
	}

	/**
	 * 检查更新结果，并必须至少更新一条数据
	 * @param $result
	 * @return bool 成功返回true，否则返回false
	 */
	static function checkUpdateResultAndLeastOne($result)
	{
		return is_numeric($result) && $result > 0;
	}

	/**
	 * 检查删除结果
	 * @param $result
	 * @return bool 成功返回true，否则返回false
	 */
	static function checkDeleteResult($result)
	{
		if (is_numeric($result)) {
			return true;
		}

		return false;
	}

	/**
	 * 检查删除结果，并必须至少删除一条数据
	 * @param $result
	 * @return bool 成功返回true，否则返回false
	 */
	static function checkDeleteResultAndLeastOne($result)
	{
		return is_numeric($result) && $result > 0;
	}
}