<?php

namespace App\Http\Controllers;

use App\Bases\BaseControllerApi;
use App\Services\ServiceThird;

/**
 * api接口
 * Class ControllerThird
 * @package App\Http\Controllers
 */
class ControllerThird extends BaseControllerApi
{
	/**
	 * 第三方接口调用
	 */
	/**
	 * 活动申请审核结果
	 * http://localhost/third/applyApprovalResult
	 */
	public function applyApprovalResult()
	{
		$this->echoReturn(ServiceThird::applyApprovalResult(
			$this->request('number',''),
			$this->request('comment', ''),
			$this->request('status', 0)
		));
	}
}
