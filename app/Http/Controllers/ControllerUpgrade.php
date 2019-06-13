<?php

namespace App\Http\Controllers;

use App\Bases\BaseController;

class ControllerUpgrade extends BaseController

{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * 升级系统
	 * http://localhost/upgrade?name=20180904
	 */
	public function upgrade()
	{
		set_time_limit(0);
		//
		$name = $this->get('name');
		if ($name === '') {
		} else {
			echo '<p style="color: red;">无任何执行</p>';
		}
	}
}
