<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Branner */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => '广告管理列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = '广告添加';
?>
<div class="branner-create">

    <div class="page-header">

    </div>
    <div class="page-header">
        <h1 style="border-left-width: 199px;margin-left: 321px; color: #00a0e9"><?= Html::encode('广告添加')?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
