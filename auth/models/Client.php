<?php

namespace auth\models;

use Yii;

/**
 * This is the model class for table "{{%sys}}".
 *
 * @property integer $id
 * @property string $sys
 * @property string $logout_url
 */
class Client extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%client}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['app_id', 'app_key', 'notify_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'app_id' => 'app_id',
            'app_key' => 'app_key',
            'notify_url' => 'notify_url',
        ];
    }

    /**
     * 验证header signKey
     * @author Allon<xianlong300@sina.com>
     * @dateTime 2015-12-11T18:03:58+0800
     * @param    [type]                   $once    临时值
     * @param    [type]                   $hashKey header signKey
     * @return   boolean                            是否合法
     */
    public function validateHashKey($once, $hashKey) {
        list($once, $hash) = $this->generateHashKey($once);
        if ($hash === $hashKey) {
            return true;
        }

        return false;
    }

    public static function getAllHashClients($ticket) {
        $clients = self::find()->all();
        $clientsData = [];
        foreach ($clients as $item) {
            $url = $item->notify_url;
            if (!strpos($url, "?")) {
                $url .= "?";
            }
            $url .= "&act=login&_ticket=" . $ticket;
            $clientsData[] = [
                'url' => $url,
            ];
        }
        return $clientsData;
    }

    public static function getClientLogoutHash() {
        $clients = self::find()->all();
        $clientsData = [];
        foreach ($clients as $item) {
            $url = $item->notify_url;
            $once = substr(md5(rand(1000, 9999)), 0, 8);
            $logoutHash = sha1($item->app_key . $once . md5($item->app_id));
            if (!strpos($url, "?")) {
                $url .= "?";
            }
            $url .= "&act=logout&logoutToken=" . $logoutHash;
            $clientsData[] = [
                'url' => $url,
            ];
        }
        return $clientsData;
    }
}
