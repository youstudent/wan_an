<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\Menu */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = '新增菜单';
?>
<div class="menu-create">

    <div class="page-header">

    </div>
    <div class="page-header">
        <h1 style="text-align: center; color: #00a0e9"><?= Html::encode('新增菜单')?></h1>
    </div>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
