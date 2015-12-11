<?php
namespace auth\controllers;
use auth\models\Client;
use auth\models\LoginModel;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Auth controller
 */
class AuthController extends Controller {
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    /**
     * 用户登录,返回各个客户端的票据以及通知路径
     * @author Allon<xianlong300@sina.com>
     * @dateTime 2015-12-09T15:12:11+0800
     * @return   [type]                   [description]
     */
    public function actionLogin() {
        $redirect_to = Yii::$app->request->get('redirect_to');
        $callback = Yii::$app->request->get('callback');
        $model = new LoginModel();
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            if ($model->validate() && $model->login()) {
                $user = Yii::$app->user->getIdentity();
                $clients = Client::getAllHashClients($user->ticket);
                $res['clients'] = $clients;
                $res['redirect'] = $redirect_to ? $redirect_to : '/';
                $res = json_encode($res);
                if ($callback) {
                    $res = $callback . "(" . $res . ")";
                }
                return $res;
            }
        }
        return $this->render('login', ['model' => $model]);
    }

    /**
     * 所有系统都跳转到sso 统一退出，退出后sso通知其他主系统进行退出，然后跳转回子系统
     * @author Allon<xianlong300@sina.com>
     * @dateTime 2015-12-09T15:17:51+0800
     * @return   [type]                   [description]
     */
    public function actionLogout() {
        $user = Yii::$app->user->getIdentity();
        $clients = Client::getClientLogoutHash();
        Yii::$app->user->logout();
        return $this->render('notify', ['clients' => $clients, 'redirect' => '/']);
    }

}
