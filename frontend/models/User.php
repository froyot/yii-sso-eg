<?php
namespace frontend\models;
use Yii;
use yii\base\Model;

class User extends Model implements \yii\web\IdentityInterface {
    public $id;
    public $username;
    public $accessToken;
    public function rules() {
        return [
            [['id', 'username', 'accessToken'], 'required'],
        ];
    }
    public function saveUserInfo() {
        Yii::$app->session->set('_id', $this->id);
        Yii::$app->session->set('_username', $this->username);
        Yii::$app->session->set('_accessToken', $this->accessToken);
    }
    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        $user = new User();
        $user->id = Yii::$app->session->get('_id');
        $user->username = Yii::$app->session->get('_username');
        $user->accessToken = Yii::$app->session->get('_accessToken');

        return $user;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {

        return null;
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username) {

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return Yii::$app->session->get('_id');
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
}
