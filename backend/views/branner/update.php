<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Branner */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => '广告管理列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = '广告修改';
?>
<div class="branner-update">

    <div class="page-header">

    </div>
    <div class="page-header">
        <h1 style="border-left-width: 199px;margin-left: 321px; color: #00a0e9"><?= Html::encode('广告修改')?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
