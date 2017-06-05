<?php

use yii\helpers\Html;

/* @var $this  yii\web\View */
/* @var $model mdm\admin\models\BizRule */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-create">
    <div class="page-header">
    
    </div>
    <div class="page-header">
        <h1 style="text-align: center; color: #00a0e9"><?= Html::encode('新增规则')?></h1>
    </div>

    <?=
    $this->render('_form', [
        'model' => $model,
    ]);
    ?>

</div>
