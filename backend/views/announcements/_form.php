<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Announcements */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="announcements-form">

    <?php     $form = ActiveForm::begin([
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'options' => ['enctype' => 'multipart/form-data']   // important, needed for file upload
    ]);?>


        <div class="row container">
        <div class="col-md-5">
            <?= $form->field($model, 'title')->textInput(['maxlength' => 30]) ?>
            
            <?= $form->field($model, 'author')->textInput(['maxlength' => 30]) ?>
            <?= $form->field($model, 'status')->dropDownList(\backend\models\Announcements::$status_option) ?>
    
            <?= $form->field($model, 'content')->widget('common\widgets\ueditor\Ueditor',[
                'options'=>[
                    'initialFrameWidth' => 558,
                ]
            ]) ?>
        </div>
        

    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
                <?= Html::submitButton($model->isNewRecord ? '添加' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

        <?php ActiveForm::end(); ?>

</div>

