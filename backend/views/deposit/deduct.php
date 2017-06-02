<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;
use kartik\widgets\SwitchInput;


/* @var $this yii\web\View */
/* @var $model backend\models\Deposit */

$this->title = '扣除';
$this->params['breadcrumbs'][] = ['label' => '充值管理', 'url' => ['deduct']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deposit-deduct">

    <?php     $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'options' => ['enctype' => 'multipart/form-data']   // important, needed for file upload
    ]);?>


    <div class="row">
        <div class="col-md-6">

            <?= $form->field($model, 'member_id')->textInput(['style'=>'width:150px']) ?>

            <?= $form->field($model, 'type')->dropDownList(['1'=>'金果', '2'=>'金种子'],
                ['prompt'=>'请选择','style'=>'width:120px'])  ?>

            <?= $form->field($model, 'num')->textInput(['style'=>'width:150px']) ?>

            <?= $form->field($model, 'operation')->hiddenInput(['value'=>2]) ?>

        </div>

    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <?= Html::submitButton('扣除', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
