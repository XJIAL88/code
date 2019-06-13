<?php

namespace App\Bases;

use App\Component\ComponentFile;
use App\Component\ComponentString;
use Illuminate\Support\Facades\Cache;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Illuminate\Support\Facades\DB;

/**
 * 业务基类
 * Class BaseService
 * @package App\Services
 */
class BaseService
{
	/**
	 * 返回成功
	 * @param array $data
	 * @return array
	 */
	static function returnSuccess($data = array())
	{
		return array(
			'code' => 0,
			'data' => $data
		);
	}

	/**
	 * 返回错误
	 * @param $code
	 * @param $message
	 * @return array
	 */
	static function returnError($code, $message)
	{
		return array(
			'code' => $code,
			'message' => $message
		);
	}

	/**
	 * AR对象转换为数组
	 * @param $arObject
	 * @return array|null
	 */
	static function arObject2Array($arObject)
	{
		if ($arObject && is_object($arObject)) {
			$result = array();
			foreach ($arObject as $key => $item) {
				$result[ComponentString::convertUnderline($key)] = $item;
			}
			return $result;
		} else {
			return null;
		}
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
	 * 数组下划线格式转换为驼峰格式
	 * @param $array
	 * @return array
	 */
	static function arrayUnderLineToHump($array)
	{
		if ($array && is_array($array)) {
			$result = array();
			foreach ($array as $key => $item) {
				$result[ComponentString::convertUnderline($key)] = $item;
			}
			return $result;
		} else {
			return null;
		}
	}

	/**
	 * 商户uuid添加下划线
	 * @param $array
	 * @return array
	 */
	static function businessUuidAddUnderLine($businessUuid)
	{
		$businessUuid = substr_replace($businessUuid, '-', 8, 0);
		$businessUuid = substr_replace($businessUuid, '-', 13, 0);
		$businessUuid = substr_replace($businessUuid, '-', 18, 0);
		$businessUuid = substr_replace($businessUuid, '-', 23, 0);
		return $businessUuid;
	}

	/**
	 * 二位数组下划线格式转换为驼峰格式
	 * @param $array
	 * @param string $sub 是否有子结构需要转换 sub为子结构所在键名
	 * @return array
	 */
	static function arraysUnderLineToHump($array, $sub = '')
	{
		$result = array();
		foreach ($array as $item) {
			if ($sub !== '' && isset($item[$sub]) && !empty($item[$sub])) {
				$item[$sub] = self::arraysUnderLineToHump($item[$sub]);
			}
			$result[] = self::arrayUnderLineToHump($item);
		}
		return $result;
	}


	/**
	 * 数组转换为树
	 * @param $array
	 * @param $key
	 * @return mixed
	 */
	static function array2tree($array, $key)
	{
		$result = $array();
		foreach ($array as $item) {
			if (!isset($result[$item[$key]])) {
				$result[$item[$key]] = array();
			}
			$result[$item[$key]][] = $item;
		}
		return $result;
	}

	/**
	 * 多维数组转换为字符串
	 * @param $array
	 * @return string
	 */
	static function array2string($array)
	{
		if (is_array($array)) {
			return implode('-', array_map(array(self::class, 'array2string'), $array));
		}
		return $array;
	}

	/**
	 * 获取缓存的Key
	 * @param $key
	 * @param array $other
	 * @return string
	 */
	static function getCacheKey($key, $other = array())
	{
		return $_ENV['PROJECT_name'] . '.' . $key . (empty($other) ? '' : '.' . self::array2string($other));
	}

	/**
	 * 获取缓存的KEYS列表
	 * @return mixed
	 */
	static function getCacheKeys()
	{
		if (($cacheResult = self::getCache($_ENV['PROJECT_name'] . '-CACHEKEYS'))) {
			return $cacheResult;
		} else {
			return array();
		}
	}

	/**
	 * 保存缓存的KEYS列表
	 * @param $keys
	 */
	static function setCacheKeys($keys)
	{
		Cache::put($_ENV['PROJECT_name'] . '-CACHEKEYS', $keys, 30);
	}

	/**
	 * 获取缓存
	 * @param $key
	 * @return mixed
	 */
	static function getCache($key)
	{
		self::log('cache', __CLASS__ . '->' . __FUNCTION__, $key);
		return Cache::get($key);
	}

	/**
	 * 设置缓存
	 * @param $key
	 * @param $data
	 * @param int $timeout
	 */
	static function setCache($key, $data, $timeout = 30)
	{
		// 保存$key至Keys集合
		if (is_array($cacheKeyList = self::getCacheKeys())) {
			if (!isset($cacheKeyList[$key])) {
				$cacheKeyList[$key] = $key;
				self::setCacheKeys($cacheKeyList);
			}
		}
		//
		self::log('cache', __CLASS__ . '->' . __FUNCTION__, $key . '/' . $timeout . '分钟');
		Cache::put($key, $data, $timeout);
	}

	/**
	 * 清除缓存
	 * @param $key
	 * @param bool $isPrefix
	 */
	static function cleanCache($key, $isPrefix = false)
	{
		if (empty($key)) {
			return;
		}
		if (is_array($cacheKeyList = self::getCacheKeys())) {
			if ($isPrefix) {
				// 清除必要的缓存
				$cacheKeyListNew = array();
				foreach ($cacheKeyList as $item) {
					if (substr($item, 0, strlen($key)) === $key) {
						self::forgetCache($item);
					} else {
						$cacheKeyListNew[$item] = $item;
					}
				}
				// 保存新的Keys集合缓存
				self::setCacheKeys($cacheKeyListNew);
			} else {
				// 清除缓存
				self::forgetCache($key);
				// 删除缓存项
				unset($cacheKeyList[$key]);
				// 保存新的Keys集合缓存
				self::setCacheKeys($cacheKeyList);
			}
		}
	}

	/**
	 * 清空缓存
	 */
	static function clearCache()
	{
		foreach (self::getCacheKeys() as $item) {
			self::forgetCache($item);
		}
		self::setCacheKeys(array());
	}

	/**
	 * 删除缓存
	 * @param $key
	 */
	static function forgetCache($key)
	{
		self::log('cache', __CLASS__ . '->' . __FUNCTION__, $key);
		Cache::forget($key);
	}

	/**
	 * 对象转换为数组
	 * @param $object
	 * @return array
	 */
	static function object2Array($object)
	{
		if (is_object($object) || is_array($object)) {
			$array = array();
			foreach ($object as $key => $item) {
				if (is_object($item) || is_array($item)) {
					$array[$key] = self::object2Array($item);
				} else {
					$array[$key] = $item;
				}
			}
			return $array;
		}
		return array();
	}

	/**
	 * 检查添加结果
	 * @param $result
	 * @return bool 成功返回true，否则返回false
	 */
	static function checkInsertResult($result)
	{
		if (is_numeric($result) && $result > 0) {
			return true;
		} else {
			return false;
		}
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
		} else {
			return false;
		}
	}

	/**
	 * 检查更新结果，并必须至少更新一条数据
	 * @param $result
	 * @return bool 成功返回true，否则返回false
	 */
	static function checkUpdateResultAndLeastOne($result)
	{
		if (is_numeric($result) && $result > 0) {
			return true;
		} else {
			return false;
		}
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
		} else {
			return false;
		}
	}

	/**
	 * 检查删除结果，并必须至少删除一条数据
	 * @param $result
	 * @return bool 成功返回true，否则返回false
	 */
	static function checkDeleteResultAndLeastOne($result)
	{
		if (is_numeric($result) && $result > 0) {
			return true;
		} else {
			return false;
		}
	}

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

	/**
	 * 导出数据基本方法(格式为cvs)
	 * @param array $columnName (数据标题名称)
	 * @param string $fileName (导出文件名称)
	 * @param int $totalExportCount (要导出的数据总数)
	 * @param int $preCount (每多少条数据循环一次)
	 * @param $callback (回调函数)
	 * @return array
	 */
	static function excelData($columnName = array(), $fileName = '', $totalExportCount = 0, $preCount = 10000, $callback)
	{
		//设置时间
		set_time_limit(0);
		//设置头部
		//header("Content-type:application/vnd.ms-excel");
		//header("Content-Disposition:filename=" . iconv("UTF-8", "GB18030", $fileName) . ".csv");
		// 打开PHP文件句柄，php://output 表示直接输出到浏览器
		//$fp = fopen('php://output', 'a');

		if ($_ENV['PROJECT_name'] === 'czy-shop-local') {
			$fileName = iconv('utf-8', 'GB18030', $fileName);
		}

		$filePath = dirname(dirname(__DIR__)) . '/public/upload/excel/result/' . $fileName . '.csv';
		if (is_file($filePath)) {
			unlink($filePath);
		}
		ComponentFile::saveFile($filePath, '.');
		$fp = fopen($filePath, "a");

		// 将中文标题转换编码，否则乱码
		foreach ($columnName as $i => $v) {
			$columnName[$i] = iconv('utf-8', 'GB18030', $v);
		}
		// 将标题名称通过fputcsv写到文件句柄
		fputcsv($fp, $columnName);
		//页数
		if (is_int($totalExportCount / $preCount)) {
			//整除不加1
			$page = intval($totalExportCount / $preCount);
		} else {
			//有余数页数加1
			$page = intval($totalExportCount / $preCount) + 1;
		}
		BaseKernel::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel-num/ 导出任务总数量:' . $totalExportCount . ' 页数: ' . $page . ' 每页数量: ' . $preCount, 'jobExecutionExcel');

		for ($i = 0; $i < $page; $i++) {
			$exportData = call_user_func_array(array($callback['className'], $callback['funcName']), array(
				'pageNumber' => $i + 1,
				'pageSize' => $preCount,
				'params' => $callback['params']
			));
			if (!empty($exportData)) {
				foreach ($exportData as $item) {
					$rows = array();
					if (!empty($item) && is_array($item)) {
						foreach ($item as $exportObj) {
							$rows[] = iconv('utf-8', 'GB18030', $exportObj);
						}
						fputcsv($fp, $rows);
					} else {
						BaseKernel::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel-error/ 获取数据出错, page ' . $page, 'jobExecutionExcel');
					}
				}
			} else {
				BaseKernel::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel-error/ 获取数据出错, page ' . $page, 'jobExecutionExcel');
			}

			BaseKernel::logInfo(__CLASS__ . '->' . __FUNCTION__, 'jobExecutionExcel-success/ 获取数据成功, page' . ($i + 1), 'jobExecutionExcel');

			// 将已经写到csv中的数据存储变量销毁，释放内存占用
			unset($exportData);
//			ob_flush();
//			flush();
		}

		fclose($fp);
		if ($_ENV['PROJECT_name'] === 'czy-shop-local') {
			$fileName = iconv('GB18030', 'utf-8', $fileName);
			$filePath = $_ENV['PROJECT_apiDomainUpload'] . 'excel/result/' . $fileName . '.csv';
		}
		return self::returnSuccess($filePath);
		exit ();

	}

	/**
	 * 分库处理
	 * @param bool $isRead
	 * @return mixed
	 */
	static function getConnection($isRead = false)
	{
		if ($isRead) {
			return $_ENV['PROJECT_databaseRead'];
		} else {
			return $_ENV['PROJECT_databaseWrite'];
		}
	}

	/**
	 * 分表处理（根据UUID获取数据表后缀）
	 * @param string $uuid
	 * @return bool|string
	 */
	static function getTableSuffix($uuid = '')
	{
		// 取UUID首位
		$uuid = substr($uuid, 0, 1);
		// 将字符串变成小写
		$uuid = strtolower($uuid);
		// 组合返回
		$uuid = '_' . $uuid;
		return $uuid;
	}

	/**
	 * 事务
	 * @param $todoCallback
	 * @param $errorCallback
	 * @return mixed
	 */
	static function transaction($todoCallback, $errorCallback)
	{
		DB::connection(self::getConnection())->beginTransaction();
		try {
			$todoResult = $todoCallback();
			if ($todoResult['code']) {
				DB::connection(self::getConnection())->rollBack();
				return $todoResult;
			}
			DB::connection(self::getConnection())->commit();
			return $todoResult;
		} catch (\Exception $e) {
			DB::connection(self::getConnection())->rollBack();
			return $errorCallback($e);
		}
	}

	/**
	 * 缓存
	 * @param $name
	 * @param $param
	 * @param $todoCallback
	 * @param int $overdue
	 * @return mixed
	 */
	static function cache($name, $param, $todoCallback, $overdue = 30)
	{
		//
		$cacheKey = self::getCacheKey($name, $param);
		// 获取缓存
		if (($cacheResult = self::getCache($cacheKey))) {
			return $cacheResult;
		}
		// 获取
		$todoResult = $todoCallback();
		if ($todoResult['code']) {
			return $todoResult;
		}
		// 设置缓存
		self::setCache($cacheKey, $todoResult, $overdue);
		//
		return $todoResult;
	}

}