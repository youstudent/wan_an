<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Announcements */


$this->params['breadcrumbs'][] = ['label' => '公告管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = '修改广告管理';
?>
<div class="announcements-update">

   <div class="page-header">
        <h1><?= Html::encode('修改广告管理')?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
