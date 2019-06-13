<?php

namespace App\Bases;

use Laravel\Lumen\Routing\Controller;
use Monolog\Logger;

/**
 * 控制器基类
 * Class BaseController
 * @package App\Http\Controllers
 */
class BaseController extends Controller
{
	private $phpInput;
	private $phpInputJson;
	private $phpInputStatus = false;
	protected $logName = 'lumen';
	protected $logFile = 'lumen';
	protected $action = '';

	public function __construct()
	{
		if (strtolower($_ENV['PROJECT_apiAllowCrossDomain']) === 'true') {
			header('Access-Control-Allow-Origin: *');
			header('Access-Control-Allow-Headers: Content-Type, Accept, Authorization, Range, Origin, x-requested-with');
			header('Access-Control-Request-Methods: POST,GET,PUT,DELETE,OPTIONS');
			header('Access-Control-Allow-Methods: POST,GET,PUT,DELETE,OPTIONS');
			header('Access-Control-Allow-Credentials: true');
			header('Access-Control-Expose-Headers: Content-Range');
			header('P3P: CP=CAO PSA OUR');
		}
		//
		if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
			exit;
		}
		// 获取接口名称
		$actions = explode('/', $this->getRequestUri());
		$this->action = $actions[count($actions) - 1];
		BaseService::log($this->logName, __CLASS__ . '->' . __FUNCTION__, 'action/' . $this->action . ' ----------------------------------------------------------------------------------');
	}

	//

	/**
	 * API结果输出（可直接使用业务类的returnSuccess、returnError的返回结果）
	 * @param $return
	 */
	protected function echoReturn($return)
	{
		$result = json_encode($return, JSON_NUMERIC_CHECK);
		BaseService::log($this->logName, __CLASS__ . '->' . __FUNCTION__, $result, Logger::INFO, $this->logFile);
		echo $result;
	}

	/**
	 * API结果成功
	 * @param array $data
	 */
	protected function echoSuccess($data = array())
	{
		$result = json_encode(array(
			'code' => 0,
			'data' => $data
		), JSON_NUMERIC_CHECK);
		BaseService::log($this->logName, __CLASS__ . '->' . __FUNCTION__, $result, Logger::INFO, $this->logFile);
		echo $result;
	}

	/**
	 * API结果错误
	 * @param $code
	 * @param $message
	 */
	protected function echoError($code, $message)
	{
		$result = json_encode(array(
			'code' => $code,
			'message' => $message
		), JSON_NUMERIC_CHECK);
		BaseService::log($this->logName, __CLASS__ . '->' . __FUNCTION__, $result, Logger::INFO, $this->logFile);
		echo $result;
	}

	/**
	 * 获取POST请求数据
	 * @param $key
	 * @param string $default
	 * @return float|int|string
	 */
	protected function post($key, $default = '')
	{
		if (isset($_POST[$key])) {
			$value = $this->formatValueWithDefaultType($_POST[$key], $default);
		} else {
			$value = $default;
		}
		BaseService::log($this->logName, __CLASS__ . '->' . __FUNCTION__, $key . '/' . $value, Logger::INFO, $this->logFile);
		return $value;
	}

	/**
	 * 获取GET请求数据
	 * @param $key
	 * @param string $default
	 * @return float|int|string
	 */
	protected function get($key, $default = '')
	{
		if (isset($_GET[$key])) {
			$value = $this->formatValueWithDefaultType($_GET[$key], $default);
		} else {
			$value = $default;
		}
		BaseService::log($this->logName, __CLASS__ . '->' . __FUNCTION__, $key . '/' . $value, Logger::INFO, $this->logFile);
		return $value;
	}

	/**
	 * 获取JSON请求数据
	 * @param $key
	 * @param string $default
	 * @return bool|float|int|string
	 */
	protected function json($key, $default = '')
	{
		if ($this->phpInputStatus === false) {
			$this->phpInput = file_get_contents("php://input");
			$this->phpInputJson = json_decode($this->phpInput);
			$this->phpInputStatus = true;
			BaseService::log($this->logName, __CLASS__ . '->' . __FUNCTION__, (strlen($this->phpInput) > 20000 ? '（md5）' . md5($this->phpInput) : $this->phpInput), Logger::INFO, $this->logFile);
		}
		if ($default === 'isset-check') {
			return isset($this->phpInputJson->$key);
		}
		if (isset($this->phpInputJson->$key)) {
			return $this->formatValueWithDefaultType($this->phpInputJson->$key, $default);
		} else {
			return $default;
		}
	}

	/**
	 * 获取请求数据
	 * 此方法同时支持POST和GET请求数据，如果配置 apiRequestOnlyPost:true 则表示只支持POST请求
	 * @param $key
	 * @param string $default
	 * @return float|int
	 */
	protected function request($key, $default = '')
	{
		if (strtolower($_ENV['PROJECT_apiRequestOnlyJson']) === 'true' || $this->json($key, 'isset-check')) {
			$value = $this->json($key, $default);
		} else {
			if (isset($_REQUEST[$key])) {
				$value = $this->formatValueWithDefaultType($_REQUEST[$key], $default);
			} else {
				$value = $default;
			}
		}
		if (is_array($value) || is_object($value)) {
			BaseService::log($this->logName, __CLASS__ . '->' . __FUNCTION__, $key . '/' . json_encode($value), Logger::INFO, $this->logFile);
		} else {
			BaseService::log($this->logName, __CLASS__ . '->' . __FUNCTION__, $key . '/' . (strlen($value) > 10000 ? '（md5）' . md5($value) : $value), Logger::INFO, $this->logFile);
		}
		return $value;
	}

	/**
	 * 获取请求URL
	 * @return string
	 */
	protected function getRequestUri()
	{
		$requestUri = '';
		if (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
			// check this first so IIS will catch
			$requestUri = $_SERVER['HTTP_X_REWRITE_URL'];
		} elseif (isset($_SERVER['REDIRECT_URL'])) {
			// Check if using mod_rewrite
			$requestUri = $_SERVER['REDIRECT_URL'];
		} elseif (isset($_SERVER['REQUEST_URI'])) {
			$requestUri = $_SERVER['REQUEST_URI'];
		} elseif (isset($_SERVER['ORIG_PATH_INFO'])) {
			// IIS 5.0, PHP as CGI
			$requestUri = $_SERVER['ORIG_PATH_INFO'];
			if (!empty($_SERVER['QUERY_STRING'])) {
				$requestUri .= '?' . $_SERVER['QUERY_STRING'];
			}
		}
		$requestUris = explode('?', $requestUri);
		return $requestUris[0];
	}

	/**
	 * 根据$default的类型格式化$value的值
	 * @param $value
	 * @param $default
	 * @return float|int
	 */
	private function formatValueWithDefaultType($value, $default)
	{
		if (is_integer($default)) {
			return intval($value);
		} elseif (is_float($default)) {
			return floatval($value);
		} else {
			return $value;
		}
	}
}
