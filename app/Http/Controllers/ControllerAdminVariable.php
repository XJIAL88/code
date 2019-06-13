<?php

namespace App\Http\Controllers;

use App\Bases\BaseControllerAdmin;
use App\Services\ServiceAdminVariable;

class ControllerAdminVariable extends BaseControllerAdmin
{

	/**
	 * 变量
	 */

	/**
	 * 获取变量
	 * http://localhost/admin/variableGet?id=1（变量ID）
	 */
	public function variableGet()
	{
		$this->echoReturn(ServiceAdminVariable::variableGet(
			$this->request('id', '')
		));
	}

	/**
	 * 获取变量列表
	 * http://localhost/admin/variableGetList?name=（名称）&desc=（描述）&pageSize=20（页数）&pageNumber=1（第几页，从1开始）
	 */
	public function variableGetList()
	{
		$this->echoReturn(ServiceAdminVariable::variableGetList(
			$this->request('name', ''),
			$this->request('desc', ''),
			$this->request('pageSize', 0),
			$this->request('pageNumber', 0)
		));
	}
	/**
	 * 增加变量
	 * http://localhost/admin/variableInsert?name=（变量名称）&value=（变量值）&desc=（变量描述）
	 */
	public function variableInsert()
	{
		$this->echoReturn(ServiceAdminVariable::variableInsert(
			$this->request('name', ''),
			$this->request('desc', ''),
			$this->request('value', '')
		));
	}
	/**
	 * 编辑变量
	 * http://localhost/admin/adUpdate?id=1（变量ID）&name=（名称）&desc=（描述）&value=（变量值）
	 */
	public function variableUpdate()
	{
		$this->echoReturn(ServiceAdminVariable::variableUpdate(
			$this->request('id', 0),
			$this->request('name', ''),
			$this->request('desc', ''),
			$this->request('value', '')
		));
	}

	/**
	 * 删除变量
	 * http://localhost/admin/variableDelete?id=（变量ID）
	 */
	public function variableDelete()
	{
		$this->echoReturn(ServiceAdminVariable::variableDelete(
			$this->request('id', 0)
		));
	}
}
