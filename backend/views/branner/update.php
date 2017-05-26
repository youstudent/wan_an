<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Branner */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => '广告管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = '广告位的修改';
?>
<div class="branner-update">

    <div class="page-header">
        <h1 style="color: #00c0ef">广告位修改</h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
