<?php

namespace App\Bases;

use Monolog\Logger;

class BaseComponent
{
	/**
	 * 信息日志
	 * @param $title
	 * @param $content
	 * @param string $file
	 */
	static function logInfo($title, $content, $file = 'lumen')
	{
		BaseService::log('component', $title, $content, Logger::INFO, $file);
	}

	/**
	 * 错误日志
	 * @param $title
	 * @param $content
	 * @param string $file
	 */
	static function logError($title, $content, $file = 'lumen')
	{
		BaseService::log('component', $title, $content, Logger::ERROR, $file);
	}

	/**
	 * 对象格式参数转换为URL格式参数
	 * @param $urlObj
	 * @return string
	 */
	static function params2url($urlObj)
	{
		$buff = "";
		foreach ($urlObj as $k => $v) {
			$buff .= $k . "=" . $v . "&";
		}
		$buff = trim($buff, "&");
		return $buff;
	}

	/**
	 * Get请求
	 * @param $url
	 * @param array $params
	 * @param int $timeout
	 * @param string $mark
	 * @param string $file
	 * @return mixed
	 */
	static function curlGet($url, $params = array(), $timeout = 30, $mark = '', $file = 'component')
	{
		$curloptUrl = $url . '?' . self::params2url($params);
		//
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'start/' . $mark . '----------------------------------', $file);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'url/' . $mark . '/' . $curloptUrl, $file);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'timeout/' . $mark . '/' . $timeout, $file);
		// 初始化curl
		$ch = curl_init();
		// 设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_URL, $curloptUrl);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		// 运行curl，结果以jason形式返回
		$res = curl_exec($ch);
		curl_close($ch);
		// 取出数据
		$data = json_decode($res, true);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'result/' . $mark . '/' . $res, $file);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'end/' . $mark . '------------------------------------', $file);
		return $data;
	}

    /**
     * Post请求
     * @param $url
     * @param $params
     * @param int $timeout
     * @param string $mark
     * @return mixed
     */
    static function curlPosts($url, $params, $timeout = 30, $mark = '')
    {
        self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'start/' . $mark . '----------------------------------', 'component');
        self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'url/' . $mark . '/' . $url, 'component');
        $paramsString = json_encode($params);
        self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'params/' . $mark . '/' . $paramsString, 'component');
        self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'timeout/' . $mark . '/' . $timeout, 'component');
        // 初始化curl
        $ch = curl_init();
        // 设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $paramsString);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($paramsString))
        );
        // 运行curl，结果以jason形式返回
        $res = curl_exec($ch);
        curl_close($ch);
        // 取出数据
        $data = json_decode($res, true);
        self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'result/' . $mark . '/' . $res, 'component');
        self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'end/' . $mark . '------------------------------------', 'component');
        return $data;
    }



	/**
	 * Post请求
	 * @param $url
	 * @param $params
	 * @param $datas
	 * @param int $timeout
	 * @param string $mark
	 * @param string $file
	 * @return mixed
	 */
	static function curlPost($url, $params, $datas, $timeout = 30, $mark = '', $file = 'component')
	{
		$curloptUrl = $url . '?' . self::params2url($params);
		//
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'start/' . $mark . '----------------------------------', $file);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'url/' . $mark . '/' . $curloptUrl, $file);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'datas/' . $mark . '/' . json_encode($datas), $file);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'timeout/' . $mark . '/' . $timeout, $file);
		// 初始化curl
		$ch = curl_init();
		// 设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_URL, $curloptUrl);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		// post数据
		curl_setopt($ch, CURLOPT_POST, 1);
		// post的变量
		curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
		// 运行curl，结果以jason形式返回
		$res = curl_exec($ch);
		curl_close($ch);
		// 取出数据
		$data = json_decode($res, true);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'result/' . $mark . '/' . $res, $file);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'end/' . $mark . '------------------------------------', $file);
		return $data;
	}

	/**
	 * PostJson请求
	 * @param $url
	 * @param $params
	 * @param $datas
	 * @param int $timeout
	 * @param string $mark
	 * @param string $file
	 * @return mixed
	 */
	static function curlPostJson($url, $params, $datas, $timeout = 30, $mark = '', $file = 'component')
	{
		$curloptUrl = $url . '?' . self::params2url($params);
		$datasString = json_encode($datas);
		//
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'start/' . $mark . '----------------------------------', $file);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'url/' . $mark . '/' . $curloptUrl, $file);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'datas/' . $mark . '/' . $datasString, $file);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'timeout/' . $mark . '/' . $timeout, $file);
		// 初始化curl
		$ch = curl_init();
		// 设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_URL, $curloptUrl);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		// post数据
		curl_setopt($ch, CURLOPT_POST, 1);
		// post的变量
		curl_setopt($ch, CURLOPT_POSTFIELDS, $datasString);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json; charset=utf-8',
				'Content-Length: ' . strlen($datasString))
		);
		// 运行curl，结果以jason形式返回
		$res = curl_exec($ch);
		curl_close($ch);
		// 取出数据
		$data = json_decode($res, true);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'result/' . $mark . '/' . $res, $file);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'end/' . $mark . '------------------------------------', $file);
		return $data;
	}

	/**
	 * 京东PostBody请求
	 * @param $url
	 * @param $params
	 * @param int $timeout
	 * @param string $mark
	 * @param string $file
	 * @return mixed
	 */
	static function jdCurlPostBody($url, $params, $timeout = 30, $mark = '', $file = 'component')
	{
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'start/' . $mark . '----------------------------------', $file);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'url/' . $mark . '/' . $url, $file);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'params/' . $mark . '/' . json_encode($params), $file);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'timeout/' . $mark . '/' . $timeout, $file);
		$postData = http_build_query($params);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // stop verifying certificate
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		$resp = curl_exec($curl);
		curl_close($curl);
		// 取出数据
		$data = json_decode($resp, true);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'result/' . $mark . '/' . $resp, $file);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'end/' . $mark . '------------------------------------', $file);
		return $data;
	}

	/**
	 * PostBody请求
	 * @param $url
	 * @param $params
	 * @param $body
	 * @param int $timeout
	 * @param string $mark
	 * @param string $file
	 * @return mixed
	 */
	static function curlPostBody($url, $params, $body, $timeout = 30, $mark = '', $file = 'component')
	{
		$curloptUrl = $url . '?' . self::params2url($params);
		//
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'start/' . $mark . '----------------------------------', $file);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'url/' . $mark . '/' . $curloptUrl, $file);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'body/' . $mark . '/' . $body, $file);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'timeout/' . $mark . '/' . $timeout, $file);
		// 初始化curl
		$ch = curl_init();
		// 设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_URL, $curloptUrl);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		// post数据
		curl_setopt($ch, CURLOPT_POST, 1);
		// post的变量
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Length: ' . strlen($body))
		);
		// 运行curl，结果以jason形式返回
		$res = curl_exec($ch);
		curl_close($ch);
		// 取出数据
		$data = json_decode($res, true);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'result/' . $mark . '/' . $res, $file);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'end/' . $mark . '------------------------------------', $file);
		return $data;
	}

	/**
	 * PostOAuth请求（彩之云的彩管家OAuth2.0相关接口专用）
	 * @param $url
	 * @param $params
	 * @param $datas
	 * @param int $timeout
	 * @param string $mark
	 * @param string $file
	 * @return mixed
	 */
	static function curlPostOAuth($url, $params, $datas, $timeout = 30, $mark = '', $file = 'component')
	{
		$curloptUrl = $url . '?' . self::params2url($params);
		//
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'start/' . $mark . '----------------------------------', $file);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'url/' . $mark . '/' . $curloptUrl, $file);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'datas/' . $mark . '/' . json_encode($datas), $file);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'timeout/' . $mark . '/' . $timeout, $file);
		// 初始化curl
		$ch = curl_init();
		// 设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_URL, $curloptUrl);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		// post数据
		curl_setopt($ch, CURLOPT_POST, 1);
		// post的变量
		curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/x-www-form-urlencoded; charset=utf-8'
			)
		);
		// 运行curl，结果以jason形式返回
		$res = curl_exec($ch);
		curl_close($ch);
		// 取出数据
		$data = json_decode($res, true);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'result/' . $mark . '/' . $res, $file);
		self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'end/' . $mark . '------------------------------------', $file);
		return $data;
	}
}
