<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['target' => "_parent"]]);?>
                <?=$form->field($model, 'username')?>
                <?=$form->field($model, 'password')->passwordInput()?>
                <div style="color:#999;margin:1em 0">
                    If you forgot your password you can <?=Html::a('reset it', ['site/request-password-reset'])?>.
                </div>
                <div class="form-group">
                    <?=Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button'])?>
                </div>
            <?php ActiveForm::end();?>
        </div>
    </div>
<?php
$js = <<<JS
function notify(url){
    $.ajax({
        dataType:'jsonp',
        url: url,
        type: 'get',
        success: function (data) {

        }
    });
}
            $('form#login-form').on('beforeSubmit', function(e) {
                var \$form = $(this);
                $.ajax({
                    url: \$form.attr('action'),
                    type: 'post',
                    data: \$form.serialize(),
                    success: function (data) {
                        var data = eval('('+data+')');
                        $(data.clients).each(function(k,v){
                            notify(v.url);
                        });
                        setTimeout(function(){
                            window.location.href=data.redirect;
                        },500);
                    }
                });
            }).on('submit', function (e) {
                e.preventDefault();
            });

JS;
$this->registerJs($js);
