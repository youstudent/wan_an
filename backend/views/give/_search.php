<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\searchs\GiveSearchs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="give-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'member_id') ?>

    <?= $form->field($model, 'give_member_id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'give_coin') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
