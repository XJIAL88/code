<?php

namespace App\Bases;


/**
 * 控制器基类-API
 * Class BaseControllerApp
 * @package App\Http\Controllers
 */
class BaseControllerMicroApi extends BaseController
{
	protected $token = '';
	protected $logName = 'api';
	protected $logFile = 'lumen';
	protected $appUuid = '';

	public function __construct()
	{
		parent::__construct();
		// 检查token
		if (empty($this->token = $this->request('token'))) {
			$this->echoError(1, '错误：token不存在');
			exit;
		}
		// 获取并检查用户是否有权限
		if (!isset($_ENV['IGNORE_PRIVILEGE']) || $_ENV['IGNORE_PRIVILEGE'] === 0) {
			$privilege = BaseServiceCzy::getPrivilege($this->token);
			if ($privilege['code']) {
				self::echoReturn($privilege);
				exit;
			}
		}

	}
}


