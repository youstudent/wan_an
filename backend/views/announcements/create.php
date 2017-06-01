<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Announcements */
$this->title = '';
$this->params['breadcrumbs'][] = ['label' => '广告添加', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="announcements-create">
    <div class="page-header">
    
    </div>
    <div class="page-header">
        <h1 style="border-left-width: 199px;margin-left: 175px; color: #00a0e9"><?= Html::encode('公告添加')?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
