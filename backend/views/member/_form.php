<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Member */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="member-form">

    <?php     $form = ActiveForm::begin([
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'options' => ['enctype' => 'multipart/form-data']   // important, needed for file upload
    ]);?>


            <div class="row">
        <div class="col-md-6">
        
            <?= $form->field($model, 'parent_vip')->textInput(['readonly'=>'true']) ?>

            <?= $form->field($model, 'mobile')->textInput(['maxlength' => 15]) ?>

            <?= $form->field($model, 'deposit_bank')->textInput(['maxlength' => 255]) ?>

            <?= $form->field($model, 'address')->textInput(['maxlength' => 255]) ?>

            <?= $form->field($model, 'username')->textInput() ?>
        </div>

        <div class="col-md-6">

            <?= $form->field($model, 'vip_number')->textInput(['readonly'=>'true']) ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => 10]) ?>

            <?= $form->field($model, 'password')->passwordInput(['maxlength' => 16, 'value'=>'']) ?>

            <?= $form->field($model, 'bank_account')->textInput(['maxlength' => 255]) ?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
                <?= Html::submitButton($model->isNewRecord ? '添加' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

        <?php ActiveForm::end(); ?>

</div>

