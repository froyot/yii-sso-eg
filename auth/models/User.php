<?php

namespace auth\models;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $ticket
 * @property string $logout_token
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface {
    public function formName() {
        return '';
    }
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['username', 'password', 'ticket'], 'string', 'max' => 255],
            ['ticket_expir', 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'ticket' => 'ticket',
            'ticket_expir' => 'ticket_expir',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return self::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        return null;
    }

    public static function findIdentityByTicket($ticket, $type = null) {
        $user = self::findOne(['ticket' => $ticket]);
        if ($user && time() - $user->ticket_expir < 0) {
            // $user->ticket_expir = time() - 1000; //登陆票据一次有效
            // $user->save();
            return $user;
        } else {
            return null;
        }
    }
    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username) {

        return self::find(['username' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return false;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function setPassword($password) {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public function validateTicket($hashKey) {
        if (!$this->validateTicketHash($hashKey)) {
            return false;
        }
        return true;

    }

    private function validateTicketHash($hashKey) {
        return true;
    }

}
