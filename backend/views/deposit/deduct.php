<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;
use kartik\widgets\SwitchInput;


/* @var $this yii\web\View */
/* @var $model backend\models\Deposit */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => '充值管理', 'url' => ['deduct']];
$this->params['breadcrumbs'][] = '扣除';
?>
<div class="deposit-deduct">

    <?php     $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_HORIZONTAL,
    ]);?>


    <div class="row">
        <div class="page-header">

        </div>  <div class="page-header">

        </div>  <div class="page-header">
            <h1 style="border-left-width: 199px;margin-left: 240px; color: #00a0e9"><?= Html::encode('扣除')?></h1>
        </div>
        <div class="col-md-6">

            <?= $form->field($model, 'username')->textInput(['style'=>'width:300px']) ?>

            <?= $form->field($model, 'type')->dropDownList(['1'=>'金果', '2'=>'金种子'],
                ['prompt'=>'请选择','style'=>'width:300px'])  ?>

            <?= $form->field($model, 'num')->textInput(['style'=>'width:300px'])->label('扣除数量') ?>

            <?= $form->field($model, 'operation')->hiddenInput(['value'=>2]) ?>

        </div>

    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <?= Html::submitButton('扣除', ['class' => 'btn btn-danger']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
