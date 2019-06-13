<?php

namespace App\Bases;


/**
 * 控制器基类-API
 * Class BaseControllerApp
 * @package App\Http\Controllers
 */
class BaseControllerApi extends BaseController
{
	protected $token = '';
	protected $logName = 'api';
	protected $logFile = 'lumen';
	protected $appUuid = '';

	public function __construct()
	{
		parent::__construct();
		$data = file_get_contents("php://input");
		$data = json_decode($data, true);

		// 检查token是否存在
		if (empty($this->token = $this->request('token'))) {
			$this->echoError(1, '错误：token不存在');
			exit;
		}

		// 验证token
		$token = self::getAccessToken($data);
		if ($token !== $data['token']){
			$this->echoError(2, 'token验证失败');
			exit;
		}

	}

	static function getAccessToken($data)
	{
		unset($data['token']);
		$data['appid'] = $_ENV['PROJECT_czyInterfaceAppId'];
		ksort($data);
		$buff = '';
		foreach ($data as $k => $v) {
			$buff .= $k . "=" . $v . "&";
		}
		$buff = trim($buff, "&");
		return  md5($buff . 'false');
	}
}


