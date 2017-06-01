<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Fruiter */

$this->title = 'Create Fruiter';
$this->params['breadcrumbs'][] = ['label' => 'Fruiters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fruiter-create">

    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
