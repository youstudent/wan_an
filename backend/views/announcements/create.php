<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Announcements */

$this->params['breadcrumbs'][] = ['label' => '广告添加', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="announcements-create">

    <div class="page-header">
        <h1><?= Html::encode('添加广告管理')?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
