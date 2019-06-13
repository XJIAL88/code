<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

define('ROUTES_BASE', '/');

$app->get(ROUTES_BASE, function () use ($app) {
    return '<font color="#999" size="1">' . $_ENV['PROJECT_name'] . '/' . $_ENV['PROJECT_title'] . '</font>';
});

$routesArray = array(

    // =================================================================================================================
    // 测试路由
    // =================================================================================================================

    'example/test' => 'ControllerExample@test',
    'example/applicationInsert' => 'ControllerExample@applicationInsert',
    'example/roleInsert' => 'ControllerExample@roleInsert',
    'example/testSql' => 'ControllerExample@testSql',

    // =================================================================================================================
    // 系统升级路由
    // =================================================================================================================

    'upgrade' => 'ControllerUpgrade@upgrade',


    // =================================================================================================================
    // 缓存
    // =================================================================================================================

    'api/cacheGet' => 'ControllerCache@cacheGet',
    'api/cacheGetList' => 'ControllerCache@cacheGetList',
    'api/cacheDelete' => 'ControllerCache@cacheDelete',
    'api/cacheClear' => 'ControllerCache@cacheClear',

    // =================================================================================================================
    // 基础路由
    // =================================================================================================================

    // 系统

    'admin/info' => 'ControllerAdmin@info',
    'admin/loginInfo' => 'ControllerAdmin@loginInfo',
    'admin/heartbeat' => 'ControllerAdmin@heartbeat',
    'admin/uploadFile' => 'ControllerAdmin@uploadFile',
    'admin/uploadFileForm' => 'ControllerAdmin@uploadFileForm',
    'admin/uploadFileQuill' => 'ControllerAdmin@uploadFileQuill',
    'admin/oauthWithTrader' => 'ControllerAdmin@oauthWithTrader',
    'admin/oauthWithPlatform' => 'ControllerAdmin@oauthWithPlatform',
    'admin/oauthLogout' => 'ControllerAdmin@oauthLogout',
    'admin/oauthLogoutUrl' => 'ControllerAdmin@oauthLogoutUrl',
    'admin/oauthCallbackWithTrader' => 'ControllerAdmin@oauthCallbackWithTrader',
    'admin/oauthCallbackWithPlatform' => 'ControllerAdmin@oauthCallbackWithPlatform',
    'api/loginInfo' => 'ControllerApp@loginInfo',
    'api/uploadFileForm' => 'ControllerApp@uploadFileForm',
    'api/uploadFile' => 'ControllerApp@uploadFile',
    'api/oauth' => 'ControllerApp@oauth',
    'api/oauthCallback' => 'ControllerApp@oauthCallback',

    // 权限

    'admin/powerGet' => 'ControllerAdminPower@powerGet',
    'admin/powerGetList' => 'ControllerAdminPower@powerGetList',
    'admin/powerInsert' => 'ControllerAdminPower@powerInsert',
    'admin/powerUpdate' => 'ControllerAdminPower@powerUpdate',
    'admin/powerDelete' => 'ControllerAdminPower@powerDelete',

    // 角色

    'admin/roleGet' => 'ControllerAdminRole@roleGet',
    'admin/roleGetList' => 'ControllerAdminRole@roleGetList',
    'admin/roleInsert' => 'ControllerAdminRole@roleInsert',
    'admin/roleUpdate' => 'ControllerAdminRole@roleUpdate',
    'admin/roleDelete' => 'ControllerAdminRole@roleDelete',
    'admin/roleGetPowerList' => 'ControllerAdminRole@roleGetPowerList',
    'admin/roleGetUserList' => 'ControllerAdminRole@roleGetUserList',
    'admin/roleDetail' => 'ControllerAdminRole@roleDetail',

    // 账户

    'admin/adminGet' => 'ControllerAdminAdmin@adminGet',
    'admin/adminGetList' => 'ControllerAdminAdmin@adminGetList',
    'admin/adminInsert' => 'ControllerAdminAdmin@adminInsert',
    'admin/adminUpdate' => 'ControllerAdminAdmin@adminUpdate',
    'admin/adminUpdatePassword' => 'ControllerAdminAdmin@adminUpdatePassword',
    'admin/adminDelete' => 'ControllerAdminAdmin@adminDelete',
    'admin/adminGetPowerList' => 'ControllerAdminAdmin@adminGetPowerList',
    'admin/adminBindTrader' => 'ControllerAdminAdmin@adminBindTrader',
    'admin/adminGetActionRecordList' => 'ControllerAdminAdmin@adminGetActionRecordList',

	//活动
	'admin/activityGet' => 'ControllerAdminActivity@activityGet',
	'admin/activityGetlist' => 'ControllerAdminActivity@activityGetlist',
	'admin/activityInsert' => 'ControllerAdminActivity@activityInsert',
	'admin/activityUpdate' => 'ControllerAdminActivity@activityUpdate',
	'admin/activityDelete' => 'ControllerAdminActivity@activityDelete',
	'admin/activityShelf' => 'ControllerAdminActivity@activityShelf',
	'admin/activityObtained' => 'ControllerAdminActivity@activityObtained',
	'admin/activityFreeResource' => 'ControllerAdminActivity@activityFreeResource',
	'admin/resourceCategoryList' => 'ControllerAdminActivity@resourceCategoryList',
	'admin/resourceGetList' => 'ControllerAdminActivity@resourceGetList',
	'admin/activityResourceDetail' => 'ControllerAdminActivity@activityResourceDetail',

	//活动奖品
	'admin/activityAwardGet' => 'ControllerAdminActivityAward@activityAwardGet',
	'admin/activityAwardGetlist' => 'ControllerAdminActivityAward@activityAwardGetlist',
	'admin/activityAwardInsert' => 'ControllerAdminActivityAward@activityAwardInsert',
	'admin/activityAwardUpdate' => 'ControllerAdminActivityAward@activityAwardUpdate',
	'admin/activityAwardShelf' => 'ControllerAdminActivityAward@activityAwardShelf',
	'admin/activityAwardObtained' => 'ControllerAdminActivityAward@activityAwardObtained',

	//抽奖
	'admin/lotteryParticipateRecordGetList' => 'ControllerAdminLottery@lotteryParticipateRecordGetList',
	'admin/lotteryRecordDataExcel' => 'ControllerAdminLottery@lotteryRecordDataExcel',


	//分组
	'admin/activityGroupGet' => 'ControllerAdminActivityGroup@activityGroupGet',
	'admin/activityGroupGetWithUsers' => 'ControllerAdminActivityGroup@activityGroupGetWithUsers',
	'admin/activityGroupGetList' => 'ControllerAdminActivityGroup@activityGroupGetList',
	'admin/activityGroupInsert' => 'ControllerAdminActivityGroup@activityGroupInsert',
	'admin/activityGroupUpdate' => 'ControllerAdminActivityGroup@activityGroupUpdate',
	'admin/activityGroupDelete' => 'ControllerAdminActivityGroup@activityGroupDelete',
	'admin/activityGroupClean' => 'ControllerAdminActivityGroup@activityGroupClean',

	//分组用户
	'admin/activityGroupUserGet' => 'ControllerAdminActivityGroupUser@activityGroupUserGet',
	'admin/activityGroupUserGetList' => 'ControllerAdminActivityGroupUser@activityGroupUserGetList',
	'admin/activityGroupUserInsert' => 'ControllerAdminActivityGroupUser@activityGroupUserInsert',
	'admin/activityGroupUserUpdate' => 'ControllerAdminActivityGroupUser@activityGroupUserUpdate',
	'admin/activityGroupUserDelete' => 'ControllerAdminActivityGroupUser@activityGroupUserDelete',
	'admin/activityGroupUserClean' => 'ControllerAdminActivityGroupUser@activityGroupUserClean',
	'admin/activityGroupUserJoin' => 'ControllerAdminActivityGroupUser@activityGroupUserJoin',
	'admin/activityGroupUserLeave' => 'ControllerAdminActivityGroupUser@activityGroupUserLeave',
	'admin/activityExcelUser' => 'ControllerAdminActivityGroupUser@activityExcelUser',
	'admin/activityUserDataExcel' => 'ControllerAdminActivityGroupUser@activityUserDataExcel',
	'admin/activityExcelUserImport' => 'ControllerAdminActivityGroupUser@activityExcelUserImport',
	'admin/activityUserInsertMore' => 'ControllerAdminActivityGroupUser@activityUserInsertMore',
	'admin/activityUserInsertMoreCheck' => 'ControllerAdminActivityGroupUser@activityUserInsertMoreCheck',
	'admin/activityUserInsertCheck' => 'ControllerAdminActivityGroupUser@activityUserInsertCheck',
	'admin/jobExecutionExcelClean' => 'ControllerAdminActivityGroupUser@jobExecutionExcelClean',



	// =================================================================================================================
	// 第三方调用
	// =================================================================================================================
	'third/applyApprovalResult' => 'ControllerThird@applyApprovalResult',


	// =================================================================================================================
	// 微服务
	// =================================================================================================================
	'api/lotteryQualificationsApply' => 'ControllerAdminMicroservice@lotteryQualificationsApply',
	'api/getLotteryResult' => 'ControllerAdminMicroservice@getLotteryResult',
	'api/qualificationsApplyAndGetLotteryResult' => 'ControllerAdminMicroservice@qualificationsApplyAndGetLotteryResult',
	'api/getLotteryList' => 'ControllerAdminMicroservice@getLotteryList',
	'api/getLotteryDetail' => 'ControllerAdminMicroservice@getLotteryDetail',
	'api/getLotteryUrl' => 'ControllerAdminMicroservice@getLotteryUrl',

	//测试微服务
//	'admin/lotteryQualificationsApply' => 'ControllerAdminActivity@lotteryQualificationsApply',
//	'admin/getLotteryResult' => 'ControllerAdminActivity@getLotteryResult',
//	'admin/qualificationsApplyAndGetLotteryResult' => 'ControllerAdminActivity@qualificationsApplyAndGetLotteryResult',


);

foreach ($routesArray as $key => $item) {
    $app->get(ROUTES_BASE . $key, $item);
    $app->post(ROUTES_BASE . $key, $item);
    $app->options(ROUTES_BASE . $key, $item);
}

