<?php
namespace api\controllers;

use Yii;

use yii\web\Controller;
use api\controllers\bases\BaseController;


/**
 * Site controller
 */
class TestController extends BaseController
{
	
	public function actionIndex()
	{
		return ['code' => 200, 'msg' => 'sucess'];
	}
	public function actionCache()
	{
		var_dump(Yii::$app->cache->get('xcx-userinfo-oGWDW5WgLI11WojAYm5xgQ07dHGw'));
		// 微信异步通知消息
		// var_dump(Yii::$app->cache->get('notify-in'), Yii::$app->cache->get('notify'), Yii::$app->cache->get('notify-result'));
	}
}