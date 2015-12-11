<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace auth\utils;
use auth\models\Client;
use auth\models\User;
use yii\filters\auth\AuthMethod;

/**
 * QueryParamAuth is an action filter that supports the authentication based on the access token passed through a query parameter.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HeaderParamAuth extends AuthMethod {

    /**
     * 验证子系统请求头，确保是子系统的请求
     */
    public function authenticate($user, $request, $response) {
        $headers = $request->headers;
        $ticket = $request->get('ticket');
        if (isset($headers['app-id']) && isset($headers['signkey']) && isset($headers['once'])) {
            if (!$this->checkHeader($headers)) {
                return null;
            }
            $user = User::findIdentityByTicket($ticket);
            if ($user != null) {
                \Yii::$app->user->login($user);
                return $user;
            }
        }
        if ($ticket !== null) {
            $this->handleFailure($response);
        }
        return null;
    }

    /**
     * 验证头
     * @author Allon<xianlong300@sina.com>
     * @dateTime 2015-12-11T18:02:01+0800
     * @param    [type]                   $headers [description]
     * @return   boolean                           是否合法
     */
    public function checkHeader($headers) {
        $client = Client::findOne(['app_id' => $headers['app-id']]);
        if (!$client) {
            return false;
        }
        if ($client->validateHashKey($headers['once'], $headers['signkey'])) {
            return true;
        }
        return false;

    }
}
