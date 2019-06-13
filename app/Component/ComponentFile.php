<?php

namespace App\Component;

use App\Bases\BaseComponent;

/**
 * 文件
 * User: Administrator
 * Date: 2016/7/13
 * Time: 16:44
 */
class ComponentFile extends BaseComponent
{
	/**
	 * 保存文件
	 * @param $filePath
	 * @param $base64Data
	 * @return bool
	 */
	static function saveFile($filePath, $base64Data)
	{
		try {
			if (is_file($filePath)) {
//				self::logInfo(__CLASS__ . '->' . __FUNCTION__, '文件已存在', 'file');
//				self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'filePath/' . $filePath, 'file');
				return true;
			}
			if (!is_dir(dirname($filePath))) {
				mkdir(dirname($filePath), 0755, true);
			}
			if (file_put_contents($filePath, base64_decode($base64Data))) {
//				self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'filePath/' . $filePath, 'file');
				return true;
			} else {
				self::logError(__CLASS__ . '->' . __FUNCTION__, '保存文件失败', 'file');
				self::logError(__CLASS__ . '->' . __FUNCTION__, 'filePath/' . $filePath, 'file');
				return false;
			}
		} catch (\Exception $e) {
			self::logError(__CLASS__ . '->' . __FUNCTION__, $e->getMessage(), 'file');
			self::logError(__CLASS__ . '->' . __FUNCTION__, 'filePath/' . $filePath, 'file');
			return false;
		}
	}

	/**
	 * 保存文件
	 * @param $filePath
	 * @param $data
	 * @return bool
	 */
	static function saveFileData($filePath, $data)
	{
		try {
			if (is_file($filePath)) {
				self::logInfo(__CLASS__ . '->' . __FUNCTION__, '文件已存在', 'file');
				self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'filePath/' . $filePath, 'file');
				return true;
			}
			if (!is_dir(dirname($filePath))) {
				mkdir(dirname($filePath), 0755, true);
			}
			if (file_put_contents($filePath, $data)) {
				self::logInfo(__CLASS__ . '->' . __FUNCTION__, 'filePath/' . $filePath, 'file');
				return true;
			} else {
				self::logError(__CLASS__ . '->' . __FUNCTION__, '保存文件失败', 'file');
				self::logError(__CLASS__ . '->' . __FUNCTION__, 'filePath/' . $filePath, 'file');
				return false;
			}
		} catch (\Exception $e) {
			self::logError(__CLASS__ . '->' . __FUNCTION__, $e->getMessage(), 'file');
			self::logError(__CLASS__ . '->' . __FUNCTION__, 'filePath/' . $filePath, 'file');
			return false;
		}
	}

	/**
	 * 文件路径（根据DATA获取）
	 * @param $fileData
	 * @param $fileSuffix
	 * @return string
	 */
	static function filePathWithData($fileData, $fileSuffix)
	{
		return $_ENV['PROJECT_apiDomainUpload'] . date('Ymd') . '/' . md5($fileData) . '.' . $fileSuffix;
	}
}