<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Member */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => '会员管理列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = '会员信息修改';
?>
<div class="member-update">

    <div class="page-header">

    </div>
    <div class="page-header">
        <h1 style="color: #00a0e9;margin-left: 281px;color: #00a0e9"><?= Html::encode('会员信息修改')?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
