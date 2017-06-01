<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Announcements */

$this->title='';
$this->params['breadcrumbs'][] = ['label' => '公告管理列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = '公告修改';
?>
<div class="announcements-update">

    <div class="page-header">

    </div>
    <div class="page-header">
        
        <h1 style="border-left-width: 199px;margin-left: 175px; color: #00a0e9"><?= Html::encode('公告修改')?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
