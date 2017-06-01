<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php     $form = ActiveForm::begin([
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'options' => ['enctype' => 'multipart/form-data']   // important, needed for file upload
    ]);?>


            <div class="row">
        <div class="col-md-6">
        
            <?= $form->field($model, 'member_id')->textInput() ?>

            <?= $form->field($model, 'price')->textInput(['maxlength' => 10]) ?>

            <?= $form->field($model, 'goods_id')->textInput() ?>
        </div>

        <div class="col-md-6">
        
            <?= $form->field($model, 'order_sn')->textInput(['maxlength' => 50]) ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => 30]) ?>

            <?= $form->field($model, 'status')->textInput() ?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

        <?php ActiveForm::end(); ?>

</div>

