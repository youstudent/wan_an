<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Branner */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => '广告管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = '广告添加';
?>
<div class="branner-create">

    <div class="page-header">
        <h1 style="color: #00c0ef">广告添加</h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
