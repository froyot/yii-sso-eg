<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?=Html::encode($this->title)?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <iframe src="http://account.shop.com/index.php?r=auth/login&systoken=site&redirect=http://www.shop.com" style="width: 400px;
    height: 389px;
    border: none;
    overflow: hidden;"></iframe>
    </div>
</div>
