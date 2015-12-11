<?php

namespace auth\models;
use Yii;
use yii\base\Model;

class LoginModel extends Model {
    public $username;
    public $password;
    private $_user;
    private $_sys;

    public function formName() {
        return '';
    }

    public function rules() {
        return [
            [['username', 'password'], 'string'],
            [['username', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $param) {
        if ($this->hasErrors()) {
            return;
        }
        $user = $this->getUser();
        if (!$user) {
            $this->addError('username', 'user not exist');
        } else {
            if (!$user->validatePassword($this->password)) {
                $this->addError('password', 'password error');
            }
        }
    }
    public function login() {
        if ($this->validate()) {
            if (Yii::$app->user->login($this->getUser(), 3600 * 24 * 30)) {
                $this->_user->ticket = sha1(md5(rand(1000, 9999)));
                $this->_user->ticket_expir = time() + 10;
                $this->_user->save();
                return true;
            }
        }
        return false;
    }

    private function getUser() {
        if ($this->_user == null) {
            $this->_user = User::findOne(['username' => $this->username]);
        }
        return $this->_user;
    }
}
