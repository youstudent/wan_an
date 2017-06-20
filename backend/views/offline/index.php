<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use kartik\datetime\DateTimePicker;
use kartik\widgets\ActiveForm;
use backend\models\Offline;

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => '关网时间', 'url' => ['off']];
?>
<div class="deposit-deduct">

    <?php     $form = ActiveForm::begin([
        'action' => ['offline/update'],
        'method'=>'post',
        'type' => ActiveForm::TYPE_INLINE,
    ]);?>

    <div class="row">
        <div class="page-header">

        </div>  <div class="page-header">

        </div>  <div class="page-header">
            <h1 style="border-left-width: 199px;margin-left: 240px; color: #00a0e9"><?= Html::encode('时间修改')?></h1>
        </div>
        <div class="col-md-6">

            &nbsp;&nbsp;&nbsp;&nbsp;开始时间:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $form->field($model, 'start_h')->hiddenInput(['id'=>'start_h'])->textInput(['style'=>'width:70px;'])->label('开始时间-时') ?>时
            <?= $form->field($model, 'start_i')->hiddenInput(['id'=>'start_i'])->textInput(['style'=>'width:70px'])->label('开始时间-分') ?>分
            <br><br><br><br>
            &nbsp;&nbsp;&nbsp;&nbsp;结束时间:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $form->field($model, 'end_h')->hiddenInput(['id'=>'end_h'])->textInput(['style'=>'width:70px'])->label('结束时间-时') ?>时
            <?= $form->field($model, 'end_i')->hiddenInput(['id'=>'end_i'])->textInput(['style'=>'width:70px'])->label('结束时间-分') ?>分

        </div>

    </div>
    <br><br>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <?= Html::submitButton('修改', ['class' => 'btn btn-danger']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>