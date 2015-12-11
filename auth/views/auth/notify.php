<?php

/* @var $this yii\web\View */
$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
$this->registerMetaTag(['name' => 'Refresh', 'content' => '5; Url=' . $redirect]);
?>


<?php foreach ($clients as $item): ?>
<script src="<?=$item['url'];?>&no_return=1"></script>
<?php endforeach;?>

<?php
$this->registerJs("
setTimeout(function(){
    window.location.href='" . $redirect . "';
  },500);
");
