<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use api\models\User;
?>
<h2 style="color: #3C8DBC;text-align: center;margin-bottom: 60px;">统计中心</h2>
<div class="cf well form-search" style="height: 68px;">
    <?php
    $this->title = '';
    $form = \yii\bootstrap\ActiveForm::begin(
        ['method'=>'get',
            'options'=>['class'=>'form-inline'],
            'action'=>\yii\helpers\Url::to(['count/index']),
        ]
    );
    echo $form->field($search,'start')->widget(\kartik\widgets\DateTimePicker::classname(), [
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'autoclose' => true,
            'todayHighlight' => true,
        ]
    ]).'&nbsp &nbsp &nbsp';
    //echo $form->field($search,'end')->textInput(['placeholder'=>'结束时间'])->label(false).'&nbsp &nbsp &nbsp';
    echo $form->field($search,'end')->widget(\kartik\widgets\DateTimePicker::classname(), [
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'autoclose' => true,
            'todayHighlight' => true,
        ]
    ]);
    echo \yii\bootstrap\Html::submitButton('查询或刷新',['class'=>'btn btn-xs btn-primary','style'=>'margin-bottom:11px;padding-left: 12px;margin-left: 19px;;']);
    \yii\bootstrap\ActiveForm::end();
    ?>
</div>
<table class="table table-hover table-bordered table-striped" >
  <thead>
    <tr class="danger" >
      <th>分享收益</th>
      <th>额外分享</th>
      <th>绩效收益</th>
      <th>报单业绩</th>
      <th>财务总支出</th>
      <th>结余</th>
      <th>总业绩</th>
      <th>总金果充值</th>
      <th>总金种子充值</th>
    </tr>
  </thead>
  <tbody>
  <tr>
      <td><?=$data['num2']?></td>
      <td><?=$data['num3']?></td>
      <td><?=$data['num1']?></td>
      <td><?=$data['num5']?></td>
      <td><?=$data['num4']?></td>
      <td><?=$data['total_money']?></td>
      <td><?=$data['num10']?></td>
      <td><?=$data['new_a']?></td>
      <td><?=$data['new_b']?></td>
  </tr>
  <!--
  <tr>
      <td>分享收益（直推）</td>
      <td>产生的所有分享奖金（包括额外分享）</td>
      <td>绩效业绩（见点）：产生的所有绩效奖金</td>
      <td>统计产生了多少个5金果的奖励</td>
      <td>成功提现总额</td>
      <td>平台充值出去的总金种子数-财务总支出</td>
      <td>会员人数*900</td>
    </tr>
  -->
  </tbody>
</table>