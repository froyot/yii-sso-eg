<?php
namespace auth\controllers;
use auth\models\Client;
use auth\utils\HeaderParamAuth;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;

/**
 *Api controller
 */
class ApiController extends Controller {
    private $client;

    public function beforeAction($action) {
        if (parent::beforeAction($action)) {
            $headers = Yii::$app->request->headers;
            $this->client = Client::findOne(['app_id' => $headers['app-id']]);
            return true;
        }

    }

    public function behaviors() {
        return ArrayHelper::merge(parent::behaviors(), [
            'authenticator' => [
                #这个地方使用`ComopositeAuth` 混合认证
                'class' => CompositeAuth::className(),
                #`authMethods` 中的每一个元素都应该是 一种 认证方式的类或者一个 配置数组
                'authMethods' => [
                    HttpBasicAuth::className(),
                    HttpBearerAuth::className(),
                    HeaderParamAuth::className(),
                ],
            ],
        ]);
    }

    /**
     * 子系统通过临时票据获取sso中的用户信息
     * @author Allon<xianlong300@sina.com>
     * @dateTime 2015-12-11T18:00:24+0800
     * @return   [type]                   [description]
     */
    public function actionGetInfo() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return Yii::$app->user->getIdentity();
    }
}
