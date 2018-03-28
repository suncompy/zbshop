<?php
namespace wechat\controllers;

use Yii;

use yii\web\Controller;
use wechat\controllers\bases\BaseController;
use wechat\helpers\WchatHelper;
use common\models\CustomerModel;
/**
 * Site controller
 */
class SiteController extends BaseController
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * 响应微信
     *
     * @return mixed
     */
    public function actionIndex()
    {
        // 验证服务器
        (new WchatHelper)->valid();
        // return $this->render('index');
    }
    /**
     * 已关注用户静默获取openid
     * @return [type] [description]
     */
    public function actionGetOpenid()
    {
        $appId = Yii::$app->params['wxconfig']['zbshop']['app_id'];
        $state = 1; // 1 为正式
        // 回调地址
        $redirect_uri = urlencode('http://wx.quutuu.com/signup');
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appId}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state={$state}#wechat_redirect";
        header("Location:".$url);
    }
    /**
     * 注册
     * @return [type] [description]
     */
    public function actionSignup()
    {
        // $openid = (new WchatHelper)->getOpenid();
        $model = new CustomerModel();
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($user = $model->signup()) {
                // 暂时不写登陆功能
                // if (Yii::$app->getUser()->login($user)) {
                    // return $this->redirect('/site/join-us');
                // }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }
    /**
     * 加入我们页面
     * @return [type] [description]
     */
    public function actionJoinUs()
    {
        
    }
}