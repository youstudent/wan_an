<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Member */

$this->title = '会员信息修改: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '会员管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['index']];
$this->params['breadcrumbs'][] = '信息修改';
?>
<div class="member-update">

    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
