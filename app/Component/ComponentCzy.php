<?php

namespace App\Component;

use App\Bases\BaseComponent;
use App\Bases\BaseService;

/**
 * 彩之云
 * User: Administrator
 * Date: 2016/7/13
 * Time: 16:44
 */
class ComponentCzy extends BaseComponent
{
	/**
	 * 结果标准处理
	 * @param $result
	 * @param bool $isEncrypt
	 * @return array
	 */
	static function __result($result, $isEncrypt = false)
	{
		if (empty($result) || ($result && isset($result['code']) && $result['code'])) {
			return BaseService::returnError('wfw' . $result['code'], isset($result['message']) ? $result['message'] : '未知错误');
		} else {
			return BaseService::returnSuccess($isEncrypt ? $result['contentEncrypt'] : $result['content']);
		}
	}

	/**
	 * 结果标准处理
	 * @param $result
	 * @return array
	 */
	static function __logoutResult($result)
	{
		if (empty($result) || ($result && isset($result['code']) && $result['code'])) {
			return BaseService::returnError('wfw' . $result['code'], isset($result['message']) ? $result['message'] : '未知错误');
		} else {
			return BaseService::returnSuccess(isset($result['message']) ? $result['message'] : '注销成功');
		}
	}

	/**
	 * 授权结果标准处理
	 * @param $result
	 * @return array
	 */
	static function __oauthResult($result)
	{
		if (empty($result) || ($result && isset($result["error"]))) {
			return BaseService::returnError(404, isset($result['error']) ? $result['error_description'] : '未知错误');
		} else {
			return BaseService::returnSuccess($result);
		}
	}

	/**
	 * 获取ICE的接口URL
	 * @param $apiName
	 * @return string
	 */
	static function __getCzyInterfaceApiUrl($apiName)
	{
		return $_ENV['PROJECT_czyInterfaceApiUrl'] . $apiName;
	}

	/**
	 * 获取ICE的接口URL
	 * @param $apiName
	 * @return string
	 */
	static function __getCzyCouponInterfaceApiUrl($apiName)
	{
		return $_ENV['PROJECT_czyCouponInterfaceApiUrl'] . $apiName;
	}


	/**
	 * 获取京东下单支付方式
	 * @return mixed
	 */
	static function __getJdPaymentType()
	{
		return $_ENV['PROJECT_jdPaymentType'];
	}

	/**
	 * 获取京东下单邮箱地址
	 * @return mixed
	 */
	static function __getJdOrderEmail()
	{
		return $_ENV['PROJECT_jdOrderEmail'];
	}

	/**
	 * 获取京东物流uuid
	 * @return mixed
	 */
	static function __getJdLogisticsUuid()
	{
		return $_ENV['PROJECT_jdLogisticsUuid'];
	}

	/**
	 * 获取ICE的APPID
	 * @return mixed
	 */
	static function __getCzyInterfaceAppId()
	{
		return $_ENV['PROJECT_czyInterfaceAppId'];
	}

	/**
	 * 将appId转换成appUuid
	 * @return mixed
	 */
	static function __getCzyInterfaceAppUuid()
	{
		return str_replace("-", "", $_ENV['PROJECT_czyInterfaceAppId']);
	}

	/**
	 * 获取ICE的Token
	 * @return mixed
	 */
	static function __getCzyInterfaceToken()
	{
		return $_ENV['PROJECT_czyInterfaceToken'];
	}

	/**
	 * 获取ICE的Secret
	 * @return mixed
	 */
	static function __getCzyInterfaceSecret()
	{
		return $_ENV['PROJECT_czyInterfaceSecret'];
	}


	/**
	 * 获取ICE的评价应用uuid
	 * @return mixed
	 */
	static function __getCzyInterfaceCommentAppUuid()
	{
		return $_ENV['PROJECT_czyInterfaceCommentAppUuid'];
	}

	/**
	 * 获取ICE的租户UUID
	 * @return mixed
	 */
	static function __getCzyInterfaceCorpUuid()
	{
		return $_ENV['PROJECT_czyInterfaceCorpUuid'];
	}

	/**
	 * 获取ICE的权限微服务应用uuid
	 * @return mixed
	 */
	static function __getCzyInterfacePowerUuid()
	{
		return $_ENV['PROJECT_czyInterfacePowerUuid'];
	}

	/**
	 * 获取Oauth的接口URL
	 * @param $apiName
	 * @return string
	 */
	static function __getCzyOauthApiUrl($apiName)
	{
		return $_ENV['PROJECT_czyOauthApiUrl'] . $apiName;
	}

	/**
	 * 获取Oauth的接口URL【后端】
	 * @param $apiName
	 * @return string
	 */
	static function __getCzyOauthAdminApiUrl($apiName)
	{
		return $_ENV['PROJECT_czyOauthAdminApiUrl'] . $apiName;
	}


	/**
	 * 获取支付-商户名称
	 * @return string
	 */
	static function __getCzyPaymentBusinessName()
	{
		return $_ENV['PROJECT_czyPaymentBusinessName'];
	}

	/**
	 * 获取支付-商户UUID
	 * @return string
	 */
	static function __getCzyPaymentBusinessUuid()
	{
		return $_ENV['PROJECT_czyPaymentBusinessUuid'];
	}

	/**
	 * 获取Sign
	 * @param $time
	 * @return string
	 */
	static function __getSign($time)
	{
		return self::__getCzyInterfaceAppId() . $time . self::__getCzyInterfaceToken() . 'false';
	}

	/**
	 * 获取加密的Sign
	 * @param $sign
	 * @return string
	 */
	static function __getEncryptSign($sign)
	{
		return md5($sign);
	}

	/**
	 * 获取Signature
	 * @param $time
	 * @return string
	 */
	static function __getSignature($time)
	{
		return self::__getCzyInterfaceAppId() . $time . self::__getCzyInterfaceToken();
	}

	/**
	 * 获取饭票--Signature
	 * @param $time
	 * @return string
	 */
	static function __getSignatureForFp($time)
	{
		return self::__getCzyInterfaceServiceUuid() . $time . self::__getCzyInterfaceServiceSecret();
	}


	/**
	 * 获取加密的Signature
	 * @param $signature
	 * @return string
	 */
	static function __getEncryptSignature($signature)
	{
		return md5($signature);
	}

	//////////////////////////////////////         微服务      ///////////////////////////////////////////////////

	/**
	 * 获取ICE的服务Uuid
	 * @return mixed
	 */
	static function __getCzyInterfaceServiceUuid()
	{
		return $_ENV['PROJECT_czyInterfaceServiceUuid'];
	}

	/**
	 * 获取ICE的服务secret
	 * @return mixed
	 */
	static function __getCzyInterfaceServiceSecret()
	{
		return $_ENV['PROJECT_czyInterfaceServiceSecret'];
	}

	/**
	 * 获取Signature
	 * @param $time
	 * @return string
	 */
	static function __getServiceSignature($time)
	{
		return self::__getCzyInterfaceServiceUuid() . $time . self::__getCzyInterfaceServiceSecret();
	}



	//////////////////////////////////////         签名算法      ///////////////////////////////////////////////////

	/**
	 * 生成随机字符串(数字+字母)
	 * @param int $length
	 * @return string
	 */
	static function randomKeys($length = 0)
	{
		$key = '';
		$pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
		for ($i = 0; $i < $length; $i++) {
			$key .= $pattern{mt_rand(0, 62)};    //生成php随机数
		}
		return $key;
	}

	// 生成签名

	/**
	 * 创建签名
	 * @param $data
	 * @param $secret
	 * @param string $sign_type
	 * @return string
	 */
	static function createSign($data, $secret, $sign_type = 'MD5')
	{
		$filter_data = self::paraFilter($data);
		$sort_data = self::argSort($filter_data);
		$stringA = self::createLinkstringUrlencode($sort_data);
		$stringSignTemp = $stringA . '&secret=' . $secret;
		if ($sign_type == 'MD5') {
			$sign = StrtoUpper(md5($stringSignTemp));
		} else if ($sign_type == 'HMAC-SHA256') {
			$sign = StrtoUpper(hash_hmac('sha256', $stringSignTemp, $secret));
		} else {
			$sign = '';
		}
		return $sign;
	}

	/**
	 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
	 * @param $para
	 * @return bool|string
	 */
	static function createLinkStringUrlEncode($para)
	{
		$arg = "";
		while (list ($key, $val) = each($para)) {
			$arg .= $key . "=" . urlencode($val) . "&";
		}
		//去掉最后一个&字符
		$arg = substr($arg, 0, count($arg) - 2);
		//如果存在转义字符，那么去掉转义
		if (get_magic_quotes_gpc()) {
			$arg = stripslashes($arg);
		}

		return $arg;
	}

	/**
	 * 对数组排序
	 * @param $para
	 * @return mixed
	 */
	static function argSort($para)
	{
		ksort($para);
		reset($para);
		return $para;
	}

	/**
	 * 除去数组中的空值和签名参数
	 * @param $para
	 * @return array
	 */
	static function paraFilter($para)
	{
		$para_filter = array();
		while (list ($key, $val) = each($para)) {
			if ($key == "sign" || $key == "sign_type" || $key == "signature"
				|| $key == "ts" || $key == "access_token" || $val == ""
			) continue;
			else    $para_filter[$key] = $para[$key];
		}
		return $para_filter;
	}

	/**
	 * 获取彩之云用户信息
	 * @param $accessToken
	 * @param $mobile
	 * @return array
	 */
	static function customerGetinfo($accessToken, $mobile)
	{
		$time = time();
		$sign = self::__getSign($time);
		$apiUrl = $_ENV['PROJECT_czyInterfaceApiUrl'] . 'v1/czyuser/czy/userInfoByMobile';

		$apiParams = array(
			'mobile' => $mobile,
			'access_token' => $accessToken,
			'sign' => self::__getEncryptSign($sign),
			'ts' => $time,
			'appID' => self::__getCzyInterfaceAppId()
		);

		return  self::__result(self::curlGet($apiUrl, $apiParams));

	}

	/**
	 * 获取彩之云用户信息2
	 * @param $accessToken
	 * @param $mobile
	 * @return array
	 */
	static function customerGetinfo2($accessToken, $mobile)
	{
		$time = time();
		$sign = self::__getSign($time);
		$apiUrl = $_ENV['PROJECT_czyInterfaceApiUrl'] . 'v1/czyprovide/customer/getinfo';

		$apiParams = array(
			'mobile' => $mobile,
			'access_token' => $accessToken,
			'sign' => self::__getEncryptSign($sign),
			'ts' => $time,
			'appID' => self::__getCzyInterfaceAppId()
		);

		return  self::__result(self::curlGet($apiUrl, $apiParams));

	}


}