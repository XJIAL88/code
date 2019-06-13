<?php

namespace App\Http\Controllers;

use App\Bases\BaseController;
use App\Bases\BaseService;
use Illuminate\Support\Facades\Cache;

class ControllerCache extends BaseController
{
	/**
	 * 获取缓存明细
	 * http://localhost/api/cacheGet?key=（*key）
	 */
	public function cacheGet()
	{
		echo json_encode(array(
			'code' => 0,
			'content' => BaseService::getCache(
				$this->request('key')
			)
		));
	}

	/**
	 * 获取缓存列表
	 * http://localhost/api/cacheGetList
	 */
	public function cacheGetList()
	{
		$pageSize = $this->request('pageSize', 20);
		$pageNumber = $this->request('pageNumber', 1);
		$cacheKeys = BaseService::getCacheKeys();
		$cacheKeysResult = array_slice($cacheKeys, ($pageNumber - 1) * $pageSize, $pageSize);
		echo json_encode(array(
			'code' => 0,
			'content' => array(
				'dataList' => $cacheKeysResult,
				'dataCount' => count($cacheKeys)
			)
		));
	}

	/**
	 * 删除缓存
	 * http://localhost/api/cacheDelete?key=（*key）
	 */
	public function cacheDelete()
	{
		BaseService::cleanCache(
			$this->request('key')
		);
		echo json_encode(array(
			'code' => 0,
			'content' => 1
		));
	}

	/**
	 * 清空缓存
	 * http://localhost/api/cacheClear
	 */
	public function cacheClear()
	{
		Cache::flush();
		echo json_encode(array(
			'code' => 0,
			'content' => 1
		));
	}
}
