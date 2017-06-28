<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model common\models\Give */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="give-form">

    <?php     $form = ActiveForm::begin([
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'options' => ['enctype' => 'multipart/form-data']   // important, needed for file upload
    ]);?>


            <div class="row">
        <div class="col-md-6">
        
            <?= $form->field($model, 'give_member_id')->textInput() ?>

            <?= $form->field($model, 'created_at')->textInput() ?>
        </div>

        <div class="col-md-6">
        
            <?= $form->field($model, 'member_id')->textInput() ?>

            <?= $form->field($model, 'type')->textInput() ?>

            <?= $form->field($model, 'give_coin')->textInput(['maxlength' => 20]) ?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

        <?php ActiveForm::end(); ?>

</div>

