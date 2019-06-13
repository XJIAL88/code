<?php

namespace App\Http\Controllers;

use App\Bases\BaseControllerAdmin;

class ControllerAdmin extends BaseControllerAdmin
{
	/**
	 * 项目信息
	 * http://localhost/admin/info
	 */

	/**
	 * 登录信息
	 * http://localhost/admin/loginInfo
	 */

	/**
	 * 心跳检测
	 * http://localhost/admin/heartbeat
	 */

	/**
	 * 上传文件
	 * http://localhost/admin/uploadFile?fileType=0（0：原图）&fileData=/9j/4AAQSkZVKlQYD/9k=&fileSuffix=jpg
	 */

	/**
	 * 上传文件（表单）
	 * http://localhost/admin/uploadFileForm?fileType=0（0：原图 1:广告图 2：商品列表 3：商品明细 4：商品分类 5:商品类别 6：应用图标）&fileName=file（上传图片控件名称）
	 */

	/**
	 * 上传文件（Quill）
	 * http://localhost/admin/uploadFileQuill?name=file（上传图片控件名称）
	 */
}
