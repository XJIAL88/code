<?php

namespace App\Component;

use App\Bases\BaseService;
use App\Manages\ManageVariable;

/**
 * 变量
 * User: Administrator
 * Date: 2016/7/15
 * Time: 19:23
 */
class ComponentVariable
{
	/**
	 * 获取变量
	 * @param $name
	 * @param string $default
	 * @return string
	 */
	static function getVariable($name, $default = '')
	{
		$variable = BaseService::arObject2Array(ManageVariable::getWith(BaseService::getConnection(), 'name', $name));
		if ($variable) {
			return $variable['value'];
		} else {
			return $default;
		}
	}

	/**
	 * 设置变量
	 * @param $name
	 * @param $value
	 * @param string $desc
	 * @return int
	 */
	static function setVariable($name, $value, $desc = '')
	{
		$variable = BaseService::arObject2Array(ManageVariable::getWith(BaseService::getConnection(), 'name', $name));
		if ($variable) {
			return ManageVariable::update(BaseService::getConnection(), $variable['id'], array(
				'name' => $name,
				'desc' => $desc,
				'value' => $value
			));
		} else {
			return ManageVariable::insert(BaseService::getConnection(), array(
				'name' => $name,
				'desc' => $desc,
				'value' => $value
			));
		}
	}

}