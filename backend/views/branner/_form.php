<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Branner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="branner-form">

    <?php     $form = ActiveForm::begin([
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'options' => ['enctype' => 'multipart/form-data']   // important, needed for file upload
    ]);?>


        <div class="row">
       

        <div class="col-md-6">
        
            <?= $form->field($model, 'name')->textInput(['maxlength' => 30]) ?>
           
            <?=$form->field($model, 'img')->fileInput()?>
            
            <?= $form->field($model, 'content')->widget('common\widgets\ueditor\Ueditor',[
                'options'=>[
                    'initialFrameWidth' => 674,
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

