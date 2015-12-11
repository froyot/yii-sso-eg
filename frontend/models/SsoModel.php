<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class SsoModel extends Model {
    public $ticket;

    public function checkLogin() {
        $userInfo = $this->getUserInfo();
        if ($userInfo) {
            $user = new User();
            $user->load($userInfo, '');
            $user->saveUserInfo();
            if (Yii::$app->user->login($user, 3600 * 24)) {
                return true;
            }
        }
        return false;
    }

    private function getUserInfo() {
        $str = $this->connectToServer('api/get-info&ticket=' . $this->ticket . '&logout_token=' . md5(rand(1000, 9999)));

        if ($str) {
            // $this->addError('tips', $str);
            $info = json_decode($str, true);
            return $info;
        } else {
            $this->addError('tips', $str);
        }
        return null;
    }

    private function checkLogout() {
        $userInfo = $this->getUserInfo();
        if (!$userInfo) {
            return false;
        }
        return true;
    }

    private function connectToServer($path, $isGet = true, $data = []) {
        $url = "http://account.shop.com/index.php?r=" . $path;
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $this->setHeader($ch);
        if (!$isGet) {
            curl_setopt($ch, CURLOPT_POST, 1);
            // post的变量
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $output = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode != 200) {
            $this->addError('get_info', $httpCode);
            $output = false;
        }
        curl_close($ch);
        return $output;
    }

    private function setHeader(&$ch) {

        $once = substr(md5(rand(1000, 9999)), 0, 8);
        $hash = sha1(Yii::$app->params['app-key'] . $once . md5(Yii::$app->params['app-id']));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'app-id: ' . Yii::$app->params['app-id'],
            'signKey:' . $hash,
            'once:' . $once,
        ));

    }
}
