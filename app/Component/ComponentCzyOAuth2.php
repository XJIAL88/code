<?php

namespace App\Component;

/**
 * 彩之云-OAuth2
 * User: Administrator
 * Date: 2016/7/13
 * Time: 16:44
 */
class ComponentCzyOAuth2 extends ComponentCzy
{
	/**
	 * 获取用户的UUID（根据OpenID）
	 * @param $openId
	 * @return array
	 */
	static function oauth2GetUuidByOpenID($openId)
	{
		//
		$time = time();
		$url = self::__getCzyInterfaceApiUrl('v1/oauth2/getUuidByOpenID');
		//
		$params = array(
			'appID' => self::__getCzyInterfaceAppId(),
			'ts' => $time,
			'sign' => self::__getEncryptSign(self::__getSign($time)),
			'open_id' => $openId
		);
		return self::__result(self::curlGet($url, $params));
	}
}