<?php

namespace App\Http\Controllers;

use App\Bases\BaseController;
use App\Bases\BaseService;
use App\Bases\BaseServiceCzy;
use App\Component\ComponentCzy;
use App\Component\ComponentCzyEmployee;
use App\Component\ComponentCzyRole;
use App\Component\ComponentRandom;
use App\Component\ComponentCzyResource;
use App\Services\ServiceAdminActivity;
use App\Services\ServiceAdminMicroservice;
use App\Services\ServiceAdminResourceApply;
use Illuminate\Support\Facades\DB;

class ControllerExample extends BaseController
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
	 * 创建权限应用 获取应用uuid
	 * @return array|string
	 */
	public function applicationInsert()
	{

		$name = $this->request('name', '资源管理');
		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return 'error';
		}
		$token = $token["data"];

		$url = ComponentCzy::__getCzyInterfaceApiUrl('v1/qxwfw/application/create');

		//组合body请求数据
		$bodyParams = array(
			'name' => $name,
			'access_token' => $token,
		);
		//组合query请求数据
		$queryParams = array(
			'appID' => ComponentCzy::__getCzyInterfaceAppId(),
			'ts' => time(),
			'sign' => ComponentCzy::__getEncryptSign(ComponentCzy::__getSign(time()))
		);
		//请求ICE接口
		return ComponentCzy::__result(ComponentCzy::curlPostJson($url, $queryParams, $bodyParams));
	}

	/**
	 * 添加超管
	 * @return array|string
	 */
	public function roleInsert()
	{
		$token = BaseServiceCzy::getAppToken();
		if ($token["code"] !== 0) {
			return 'error';
		}
		return ComponentCzyRole::qxwfwRoleCreate('超级管理员', 2, 0, $token["data"]);
	}


    /**
     * testSql
     * http://localhost/example/testSql?sql=sql&list=0
     */
    public function testSql()
    {
        if (!(
            $_ENV['PROJECT_name'] === 'czy-activity-testing'
        )
        ) {
            $this->echoError(1003, '没有权限~');
            exit;
        }
        if ($this->get('password') !== $_ENV['PROJECT_name']) {
            $this->echoError(1003, '没有权限~');
            exit;
        }
        try {
            if ($this->request('list', 0) === 0) {
                $sql = $this->request('sql');
                $sqlArray = explode(';', $sql);
                $result = array();
                foreach ($sqlArray as $item) {
                    if (trim($item)) {
                        $result[] = DB::connection('mysql_write')->statement(trim($item) . ';');
                    }
                }
            } else {
                $result = DB::connection('mysql_write')->select($this->request('sql'));
            }
            $this->echoSuccess($result);
        } catch (\Exception $e) {
            $this->echoError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * http://localhost/example/test
     */
    public function test()
    {
    	$index = $this->request('index',1);
    	switch ($index)
		{
			case 1:
				$appId="kjuyq1hNcSP18SyzlWru5HlL0PMgXIRX";
				$orderNumber="zenggp001";
				$resourceId=3;
				$number=10;
				$activity="抽奖";
				$type=2;
				$mobile="13755174909";
				$tokenRes = BaseServiceCzy::getAppToken();
				$username = "user_13755174909";
				$token= $tokenRes['data'];
				$res = ComponentCzyResource::useResources($appId,$orderNumber,$resourceId,$number,$activity,$type,$mobile,$username,$token);
				return $res;
				break;

			case 2:
				$appId="ICEHDPT0-KVTQ-R9B4-KHC4-NMRS3LU5YRNJ";
				$orderNumber="CJ-10006";
				$resourceId=5;
				$number=1;
				$activity="抽奖";
				$type=2;
				$mobile="13755174909";
				$tokenRes = BaseServiceCzy::getAppToken();
				$username = "user_13755174909";
				$token= $tokenRes['data'];

				$res = ServiceAdminMicroservice::useActivityResource($appId, $orderNumber, $activity, $resourceId, $number, $type, $mobile, $username);
				return $res;
				break;
			case 3:
				$token = BaseServiceCzy::getAppToken();
				if ($token["code"] !== 0) {
					return BaseService::returnError( '1011', $token["message"]);
				}
				$token = $token["data"];
				$appid = ComponentRandom::genRandomStr(32);
				return ComponentCzyRole::test('sdkfhdsijk',$appid,$token);
				break;
			case 4:
				$appid ='AzSbwHafbrWE2O1M4tX4bt2QxzMxakJq';
				$appName = $this->request('appName');
				$username = $this->request('username');
				$orderNumber = $this->request('orderNumber');
				$activity = $this->request('activity');
				$callbackUrl = $this->request('callbackUrl');
				return ServiceAdminResourceApply::resourceAccountSync($appid, $appName, $username, $orderNumber, $activity, $callbackUrl);
				break;
			case 5:
				//添加账号
				$tokenRes = BaseServiceCzy::getAppToken();
				//return $tokenRes;
				$username = "luochaomin";
				$token= $tokenRes['data'];
				return  ComponentCzyEmployee::employeeAccount("", $username, $token);
				break;
			case 6:
				//账号查询
				$token = BaseServiceCzy::getAppToken();
				if ($token["code"] !== 0) {
					return self::returnError('1011', $token["message"]);
				}
				$token = $token["data"];

				$pano = 'kxcv89z98qwsd9q3ldf9asld1sdfsg63';
				$bano = '80053728763';
				$needSubAccountInfo = 1;
				//return ComponentCzyResource::jrptAccountQueryUserAccountByUuid($token, $pano, $bano, $needSubAccountInfo);

				return ComponentCzyResource::czyuserCzyFinanceAccountInfo($token, '', '13428901875', '', 2);

				//return ComponentCzyResource::jrptTransactionFasttransaction($token, 0.01, time(), 'zenggp测试', 2, '80095969906', 2, '07accfd90cb54807abd6f179ea492576', '', '', 'zenggp--cs', '', '', 'zenggp-test', '', '', 0, '', '', '');

				break;
			case 7:
				$url = 'http://ssfw.szcourt.gov.cn/frontend/anjiangongkai/caseOpen/query?page=&pageLimit=&caseNo=&appliers=万兴尧';
				$paramsString = json_encode([
					'page' => '',
					'pageLimit' => '',
					'caseNo' => '',
					'appliers' => '万兴尧',
				]);
				$paramsString = array('appliers:万兴尧','language:zh','region:GZ');
				$ch = curl_init();
				// 设置超时
				curl_setopt($ch, CURLOPT_TIMEOUT, 30);
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
				curl_setopt($ch, CURLOPT_HEADER, FALSE);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $paramsString);
				// 运行curl，结果以jason形式返回
				$res = curl_exec($ch);

				return $res;

				break;

			case 8:
				$this->echoReturn(ServiceAdminActivity::jobExecutionActivity(
					$this->request('orderNumber', '')
				));
				break;



		}










    }
}
