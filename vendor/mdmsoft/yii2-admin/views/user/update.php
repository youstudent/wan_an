<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\User */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => '管理员列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = '信息修改';
?>
<div class="user-update">

    <div class="page-header">

    </div>
    <div class="page-header">
        <h1 style="color: #00a0e9;margin-left: 281px;color: #00a0e9"><?= Html::encode('信息修改')?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
