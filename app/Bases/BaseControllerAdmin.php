<?php

namespace App\Bases;

use App\Component\ComponentCzyAuthorize;
use App\Component\ComponentFile;
use App\Component\ComponentImage;

/**
 * 控制器基类-管理平台API
 * Class BaseControllerAdmin
 * @package App\Http\Controllers
 */
class BaseControllerAdmin extends BaseController
{
	protected $admin = null;
	protected $adminId = 0;
	protected $adminUsername = '';
	protected $adminNickname = '';
	protected $adminToken = '';
	protected $adminIsLogin = false;
	protected $adminSuper = 0;
	protected $adminPower = null;
	protected $logName = 'admin';
	protected $logFile = 'lumen';

	public function __construct()
	{
		parent::__construct();

		// 获取身份信息
		$adminId = $this->request('adminId', 0);
		$adminToken = $this->request('adminToken');

		// 特殊处理的接口
		if ($this->useGetAdminAction($this->action) === true) {
			$adminId = $this->get('adminId', 0);
			$adminToken = $this->get('adminToken');
		}

		// 检查接口
		if ($this->filterAction($this->action) === true) {

			// 不需要身份验证的API

		} else {

			// 获取账户的登录状态，身份信息
			$this->admin = BaseServiceAdmin::isLogin($adminId, $adminToken);
			$this->adminIsLogin = is_numeric($this->admin) ? false : true;

			// 设置用户的相关参数
			if ($this->adminIsLogin) {
				$this->adminId = $adminId;
				$this->adminToken = $adminToken;
				$this->adminUsername = $this->admin['username'];
				$this->adminNickname = $this->admin['nickname'];
				$this->adminSuper = intval($this->admin['roleSuper']);
				$this->adminPower = $this->admin['rolePower'];
			}

			//
			if ($this->filterActionButGet($this->action) === true) {

				// 不需要身份验证的API，但需要获取身份信息

			} else {

				// 检查用户是否登录
				if (!$this->adminIsLogin) {
					if (intval($this->admin) === -1) {
						$this->echoError(1003, '您的账户已在别处登录，请检查账户安全后重新登录。');
					} else if (intval($this->admin) === -2) {
						$this->echoError(1004, '检测到距您最后一次操作已超过2小时，为了保障数据的安全，请您重新登录。');
					} else if (intval($this->admin) === -3) {
						$this->echoError(1001, '用户身份验证失败，请重新登录。');
					}
					exit;
				}

				// 检查用户权限
				if ($this->adminSuper == 2) {

					// 超级管理员（无视权限配置）

				} else { // 管理员

					// 处理权限变量，防止变量不是数组类型
					if (!is_array($this->adminPower)) {
						$this->adminPower = array();
					}

					// 基础权限，所有用户都有的
					$this->adminPower[] = 'loginInfo';
					$this->adminPower[] = 'heartbeat';
					$this->adminPower[] = 'uploadFile';

					// 检查用户的接口权限
//					if (!in_array($this->action, $this->adminPower)) {
//						$this->echoError(1002, '用户权限不够');
//						exit;
//					}

				}
			}
		}


	}

	/**
	 * 过滤Action的任何检查
	 * @param $action
	 * @return bool
	 */
	public function filterAction($action)
	{
		if ($action === 'info' ||
			$action === 'uploadFileForm' ||
			$action === 'oauthWithTrader' ||
			$action === 'oauthWithPlatform' ||
			$action === 'oauthCallbackWithTrader' ||
			$action === 'oauthCallbackWithPlatform'
		) {
			return true;
		}
		return false;
	}

	/**
	 * 过滤Action，但获取信息
	 * @param $action
	 * @return bool
	 */
	public function filterActionButGet($action)
	{
		return false;
	}

	/**
	 * 使用get方式获取用户身份信息
	 * @param $action
	 * @return bool
	 */
	public function useGetAdminAction($action)
	{
		if ($action === 'uploadFileQuill') {
			return true;
		}
		return false;
	}

	/**
	 * 项目信息
	 * http://localhost/admin/info
	 */
	public function info()
	{
		$this->echoSuccess(array(
			'name' => $_ENV['PROJECT_name'],
			'title' => $_ENV['PROJECT_title']
		));
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 彩之云授权登陆（Oauth2.0）

	/**
	 * 商户平台授权
	 * http://localhost/admin/oauthWithTrader?url=（回调URL）
	 */
	public function oauthWithTrader()
	{
		$url = $this->get('url', '');
		// 保存商户的URL
        $appDomain = substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], ':'));
		setcookie('__TRADER_CALLBACK_URL__', $url, null, '/', $appDomain);
		//
		$redirectUri = $_ENV['PROJECT_apiDomain'] . 'admin/oauthCallbackWithTrader';
		$authorizeUrl = ComponentCzyAuthorize::oauth2AuthorizeWithWebByTrader(urlencode($redirectUri), 'oauth');
		BaseServiceAdmin::logInfo("跳转授权地址【商户】", 'redirectUri/' . $redirectUri, 'oauth');
		BaseServiceAdmin::logInfo("跳转授权地址【商户】", 'authorizeUrl/' . $authorizeUrl, 'oauth');
		header('location:' . $authorizeUrl);
	}

	/**
	 * 运营平台授权
	 * http://localhost/admin/oauthWithPlatform?url=（回调URL）
	 */
	public function oauthWithPlatform()
	{
		$url = $this->get('url', '');
		// 保存商户的URL
        $appDomain = substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], ':'));
		setcookie('__PLATFORM_CALLBACK_URL__', $url, null, '/', $appDomain);
		//
		$redirectUri = $_ENV['PROJECT_apiDomain'] . 'admin/oauthCallbackWithPlatform';
		$authorizeUrl = ComponentCzyAuthorize::oauth2AuthorizeWithWebByPlatform(urlencode($redirectUri), 'oauth', 'cgj');
		BaseServiceAdmin::logInfo("跳转授权地址【运营】", 'redirectUri/' . $redirectUri, 'oauth');
		BaseServiceAdmin::logInfo("跳转授权地址【运营】", 'authorizeUrl/' . $authorizeUrl, 'oauth');
		header('location:' . $authorizeUrl);
	}

	/**
	 * 退出（仅限运营平台使用）
	 * http://localhost/admin/oauthLogout
	 */
	public function oauthLogout()
	{
		return ComponentCzyAuthorize::oauth2Logout();
	}

	/**
	 * 退出（仅限运营平台使用）
	 * http://localhost/admin/oauthLogoutUrl
	 */
	public function oauthLogoutUrl()
	{
		return ComponentCzyAuthorize::oauth2LogoutUrl();
	}

	/**
	 * 商户授权回调
	 */
	public function oauthCallbackWithTrader()
	{
		// 获取用户的URL
		$url = isset($_COOKIE['__TRADER_CALLBACK_URL__']) ? $_COOKIE['__TRADER_CALLBACK_URL__'] : '';
		if (empty($url)) {
			BaseServiceAdmin::logError("授权回调地址【商户】", 'url获取失败', 'oauth');
			BaseServiceAdmin::logError("授权回调地址【商户】", '--------------结束---------------', 'oauth');
			return;
		}
		setcookie('__TRADER_CALLBACK_URL__');
		BaseServiceAdmin::logInfo(__CLASS__ . '->' . __FUNCTION__, '__TRADER_CALLBACK_URL__：' . $url);
		// 检查Code
		$code = $this->get('code', '');
		if (empty($code)) {
			BaseServiceAdmin::logError("授权回调地址【商户】", 'code获取失败', 'oauth');
			BaseServiceAdmin::logError("授权回调地址【商户】", '--------------结束---------------', 'oauth');
			return;
		}
		// 根据code获取授权AccessToken【与前端共用】
		$result = ComponentCzyAuthorize::oauthAccessToken($code);
		if ($result['code']) {
			BaseServiceAdmin::logError("授权回调地址【商户】", 'AccessToken获取失败', 'oauth');
			BaseServiceAdmin::logError("授权回调地址【商户】", '根据code获取token返回信息：' . json_encode($result), 'oauth');
			BaseServiceAdmin::logError("授权回调地址【商户】", '--------------结束---------------', 'oauth');
			return;
		}
		//根据AccessToken查询用户信息
		$accessToken = $result['data']['access_token'];
		$userInfo = ComponentCzyAuthorize::oauthUserInfo($accessToken);
		if ($userInfo['code']) {
			BaseServiceAdmin::logError("授权回调地址【商户】", '根据AccessToken查询用户信息失败', 'oauth');
			BaseServiceAdmin::logError("授权回调地址【商户】", '根据AccessToken查询用户信息,返回错误：' . json_encode($result), 'oauth');
			BaseServiceAdmin::logError("授权回调地址【商户】", '--------------结束---------------', 'oauth');
			header('location:' . $url);
			return;
		}
		// 登录
		$loginResult = BaseServiceAdmin::loginOauthByTrader($userInfo["data"]["mobile"], $userInfo["data"]["nickname"]);
		if ($loginResult['code']) {
			echo '<script>alert(\'' . $loginResult['message'] . '\');</script>';
			BaseServiceAdmin::logError("授权回调地址【商户】", '用户登录失败,返回信息：' . $loginResult['message'], 'oauth');
			BaseServiceAdmin::logError("授权回调地址【商户】", '--------------结束---------------', 'oauth');
			return;
		}
		// 跳转
		self::__gotoUrlWithTrade($loginResult['data'], $url);
	}

	/**
	 * 运营平台授权回调
	 */
	public function oauthCallbackWithPlatform()
	{
		// 获取用户的URL
		$url = isset($_COOKIE['__PLATFORM_CALLBACK_URL__']) ? $_COOKIE['__PLATFORM_CALLBACK_URL__'] : '';
		if (empty($url)) {
			BaseServiceAdmin::logError("授权回调地址【运营】", 'url获取失败', 'oauth');
			BaseServiceAdmin::logError("授权回调地址【运营】", '--------------结束---------------', 'oauth');
			return;
		}
		setcookie('__PLATFORM_CALLBACK_URL__');
		BaseServiceAdmin::logInfo(__CLASS__ . '->' . __FUNCTION__, '__PLATFORM_CALLBACK_URL__：' . $url);

		//判断管理还是申请品台
		$type = 1; //管理平台
		if (strripos($url, 'application')) {
			$type = 2; //申请平台
		}

		// 检查Code
		$code = $this->get('code', '');
		if (empty($code)) {
			BaseServiceAdmin::logError("授权回调地址【运营】", 'code获取失败', 'oauth');
			BaseServiceAdmin::logError("授权回调地址【运营】", '--------------结束---------------', 'oauth');
			return;
		}
		// 根据code获取授权AccessToken
		$result = ComponentCzyAuthorize::oauthAccessTokenByPlatform($code, $url, '');
		if ($result['code']) {
			BaseServiceAdmin::logError("授权回调地址【运营】", 'AccessToken获取失败', 'oauth');
			BaseServiceAdmin::logError("授权回调地址【运营】", '根据code获取AccessToken返回信息：' . json_encode($result), 'oauth');
			BaseServiceAdmin::logError("授权回调地址【运营】", '--------------结束---------------', 'oauth');
			return;
		}
		$userName = $result['data']['username'];
		// 登录
		$loginResult = BaseServiceAdmin::loginOauthByPlatform($userName,$type);
		if ($loginResult['code']) {
			echo '<script>alert(\'' . $loginResult['message'] . '\');</script>';
			BaseServiceAdmin::logError("授权回调地址【运营】", '用户登录失败,返回信息：' . $loginResult['message'], 'oauth');
			BaseServiceAdmin::logError("授权回调地址【运营】", '--------------结束---------------', 'oauth');
			return;
		}
		// 跳转
		self::__gotoUrlWithPlatform($loginResult['data'], $url);
	}

	/**
	 * 商户跳转
	 * @param $loginResult
	 * @param $url
	 */
	private function __gotoUrlWithTrade($loginResult, $url)
	{
		if ($loginResult) { // 登录成功
            $appDomain = substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], ':'));
			$cookieResult = array();
			$cookieResult[] = setcookie('__ADMIN_TRADER_ID__', $loginResult['id'], null, '/', $appDomain);
			$cookieResult[] = setcookie('__ADMIN_TRADER_NAME__', $loginResult['name'], null, '/', $appDomain);
			$cookieResult[] = setcookie('__ADMIN_TRADER_TOKEN__', $loginResult['token'], null, '/', $appDomain);
			BaseServiceAdmin::logInfo(__CLASS__ . '->' . __FUNCTION__ . '.appDomain', $appDomain, 'oauth');
			BaseServiceAdmin::logInfo(__CLASS__ . '->' . __FUNCTION__ . '.cookieResult', json_encode($cookieResult), 'oauth');
		}
		BaseServiceAdmin::logInfo(__CLASS__ . '->' . __FUNCTION__ . '.loginResult', json_encode($loginResult), 'oauth');
		BaseServiceAdmin::logInfo(__CLASS__ . '->' . __FUNCTION__ . '.gotoUrl', $url, 'oauth');
		header('location:' . $url);
	}

	/**
	 * 运营平台跳转
	 * @param $loginResult
	 * @param $url
	 */
	private function __gotoUrlWithPlatform($loginResult, $url)
	{
		if ($loginResult) { // 登录成功
            $appDomain = substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], ':'));
			$cookieResult = array();
			if (strripos($url, 'application')) {
				//申请平台
				$cookieResult[] = setcookie('__ADMIN_APPLICATION_ID__', $loginResult['id'], null, '/', $appDomain);
				$cookieResult[] = setcookie('__ADMIN_APPLICATION_NAME__', $loginResult['name'], null, '/', $appDomain);
				$cookieResult[] = setcookie('__ADMIN_APPLICATION_TOKEN__', $loginResult['token'], null, '/', $appDomain);
			} elseif (strripos($url, 'management')) {
				//管理平台
				$cookieResult[] = setcookie('__ADMIN_PLATFORM_ID__', $loginResult['id'], null, '/', $appDomain);
				$cookieResult[] = setcookie('__ADMIN_PLATFORM_NAME__', $loginResult['name'], null, '/', $appDomain);
				$cookieResult[] = setcookie('__ADMIN_PLATFORM_TOKEN__', $loginResult['token'], null, '/', $appDomain);
			}
			BaseServiceAdmin::logInfo(__CLASS__ . '->' . __FUNCTION__ . '.appDomain', $appDomain, 'oauth');
			BaseServiceAdmin::logInfo(__CLASS__ . '->' . __FUNCTION__ . '.cookieResult', json_encode($cookieResult), 'oauth');
		}
		BaseServiceAdmin::logInfo(__CLASS__ . '->' . __FUNCTION__ . '.loginResult', json_encode($loginResult), 'oauth');
		BaseServiceAdmin::logInfo(__CLASS__ . '->' . __FUNCTION__ . '.gotoUrl', $url, 'oauth');
		header('location:' . $url);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 身份相关

	/**
	 * 身份相关
	 */

	/**
	 * 登录信息
	 * http://localhost/admin/loginInfo
	 */
	public function loginInfo()
	{
		$this->echoSuccess($this->admin);
	}

	/**
	 * 心跳检测
	 * http://localhost/admin/heartbeat
	 */
	public function heartbeat()
	{
		$this->echoSuccess(1);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 文件处理

	/**
	 * 文件处理
	 */

	/**
	 * 上传文件
	 * http://localhost/admin/uploadFile?fileType=0（0：原图）&fileData=/9j/4AAQSkZVKlQYD/9k=&fileSuffix=jpg
	 */
	public function uploadFile()
	{
		$fileType = $this->request('fileType', 0);
		$fileData = $this->request('fileData');
		$fileSuffix = $this->request('fileSuffix');
		$filePath = ComponentFile::filePathWithData($fileData, $fileSuffix);
		// 数据检查
		if (empty($fileData) || empty($fileSuffix)) {
			$this->echoError(1900, '请上传图片类型的文件');
		}
		// 保存文件
		try {
			if (ComponentFile::saveFile($filePath, $fileData)) {
				if (ComponentImage::isImage($filePath)) {
					// 切图
					$resizeToSizeArray = array(
						array(
							array(56, 56)
						)
					);
					if (empty($resizeToSizeArray[$fileType])) {
						$this->echoError(1902, '类型不存在');
					}
					$resizeToFilePathArray = array($filePath);
					foreach ($resizeToSizeArray[$fileType] as $item) {
						$resizeToFilePathArray[] = ComponentImage::resizeTo($filePath, $item[0], $item[1]);
					}
					// 切图结果检查
					foreach ($resizeToFilePathArray as $item) {
						if ($item === false) {
							$this->echoError(1901, '图片处理失败，请重试');
							return;
						}
					}
					// Result
					$this->echoSuccess($_ENV['PROJECT_apiDomainUpload'] . date('Ymd') . '/' . basename($filePath));
				} else {
					unlink($filePath);
					$this->echoError(1900, '请上传图片类型的文件');
				}
			}
		} catch (\Exception $e) {
			unlink($filePath);
			$this->echoError(1900, '请上传图片类型的文件');
		}
	}

	/**
	 * 上传文件（表单）
	 * http://localhost/admin/uploadFileForm?fileType=0（0：原图1:广告图2：商品列表3：商品明细4：商品分类5：商品类别6：应用图标）&fileName=file（上传图片控件名称）
	 */
	public function uploadFileForm()
	{
		//获取上传图片控件名字、上传图片类型
		$fileType = $this->post('fileType', 0);
		$fileName = $this->post('fileName', "");
		if (empty($fileName)) {
			$this->echoError(1012, '请上传图片控件名称');
			return;
		}
		//获取数据
		$fileData = base64_encode(file_get_contents($_FILES[$fileName]['tmp_name']));
		$fileSuffix = substr($_FILES[$fileName]['name'], strrpos($_FILES[$fileName]['name'], '.') + 1);
		$filePath = ComponentFile::filePathWithData($fileData, $fileSuffix);
		// 数据检查
		if (empty($fileData) || empty($fileSuffix)) {
			$this->echoError(1900, '请上传图片类型的文件');
		}
		// 保存文件
		try {
			if (ComponentFile::saveFile($filePath, $fileData)) {
				if (ComponentImage::isImage($filePath)) {
					// 切图
					$resizeToSizeArray = array(
						array(    //原图
							array(128, 128)
						),
						array(    //广告图片
							array(128, 128),
							array(750, 340)
						),
						array(    //商品列表图片
							array(128, 128),
							array(360, 360),
							array(180, 180)
						),
						array(    //商品明细图片
							array(128, 128),
							array(256, 256),
							array(512, 512),
							array(750, 750)
						),
						array(    //商品分类图片
							array(128, 128),
							array(180, 180)
						),
						array(    //商品类别图片
							array(128, 128),
							array(180, 80)
						),
						array(    //应用图标
							array(128, 128)
						),
					);
					$resizeToFilePathArray = array($filePath);
					foreach ($resizeToSizeArray[$fileType] as $item) {
						$resizeToFilePathArray[] = ComponentImage::resizeTo($filePath, $item[0], $item[1]);
					}
					// 切图结果检查
					foreach ($resizeToFilePathArray as $item) {
						if ($item === false) {
							$this->echoError(1011, '图片处理失败，请重试');
							return;
						}
					}
					// 返回
					$this->echoSuccess($_ENV['PROJECT_apiDomainUpload'] . date('Ymd') . '/' . basename($filePath));
				} else {
					unlink($filePath);
					$this->echoError(1901, '请上传图片类型的文件');
				}
			}
		} catch (\Exception $e) {
			unlink($filePath);
			$this->echoError(19011, '请上传图片类型的文件');
		}
	}

	/**
	 * 上传文件（Quill）
	 * http://localhost/admin/uploadFileQuill
	 */
	public function uploadFileQuill()
	{
		//获取上传图片控件名字、上传图片类型
		$fileType = 0;
		$fileName = 'img';
		if (empty($fileName)) {
			$this->echoError(1012, '请上传图片控件名称');
			return;
		}
		if (!isset($_FILES[$fileName])) {
			$this->echoError(1012, '图片控件名称不对');
			return;
		}
		//获取数据
		$fileData = base64_encode(file_get_contents($_FILES[$fileName]['tmp_name']));
		$fileSuffix = substr($_FILES[$fileName]['name'], strrpos($_FILES[$fileName]['name'], '.') + 1);
		$filePath = ComponentFile::filePathWithData($fileData, $fileSuffix);
		// 数据检查
		if (empty($fileData) || empty($fileSuffix)) {
			$this->echoError(1900, '请上传图片类型的文件');
		}
		// 保存文件
		try {
			if (ComponentFile::saveFile($filePath, $fileData)) {
				if (ComponentImage::isImage($filePath)) {
					// 切图
					$resizeToSizeArray = array(
						array(    //商品明细图片
							array(750, 0)
						)
					);
					$resizeToFilePathArray = array($filePath);
					foreach ($resizeToSizeArray[$fileType] as $item) {
						$resizeToFilePathArray[] = ComponentImage::resizeTo($filePath, $item[0], $item[1]);
					}
					// 切图结果检查
					foreach ($resizeToFilePathArray as $item) {
						if ($item === false) {
							$this->echoError(1011, '图片处理失败，请重试');
							return;
						}
					}
					// 返回
					$this->echoSuccess($_ENV['PROJECT_apiDomain'] . $_ENV['PROJECT_apiDomainUpload'] . date('Ymd') . '/' . basename($filePath));
				} else {
					unlink($filePath);
					$this->echoError(1901, '请上传图片类型的文件');
				}
			}
		} catch (\Exception $e) {
			unlink($filePath);
			$this->echoError(19011, '请上传图片类型的文件');
		}
	}
}
