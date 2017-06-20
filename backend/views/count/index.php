<?php
use common\components\Helper;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use api\models\User;
use yii\widgets\LinkPager;

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

<?php
$log = \common\models\Bonus::find()->where(['coin_type'=>1, 'type'=> [1,2,3,5,6,7,10]])->orderBy(['created_at'=> SORT_DESC]);
$params = Yii::$app->request->getQueryParam('CountSearch');

if(isset($params['start']) && !empty($params['start'])){
    $log->andFilterWhere(['>=', 'created_at', strtotime($params['start'])]);
}
if(isset($params['end']) && !empty($params['end'])){
    $log->andFilterWhere(['<=', 'created_at', strtotime($params['end'])]);
}
$pages = new Pagination(['totalCount' =>$log->count(), 'pageSize' => '10']);
$log_model = $log->offset($pages->offset)->limit($pages->limit)->all();


?>


<table class="table table-hover table-bordered table-striped" >
    <thead>
    <tr class="danger" >
        <th>会员名</th>
        <th>类型</th>
        <th>金额</th>
        <th>说明</th>
        <th>时间</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($log_model as $val){
        $note = json_decode($val['ext_data'], true);
        ?>
        <tr>
            <td><?= Helper::memberId2Username($val['member_id']) ?></td>
            <td>
                <?php
                switch ($val['type']){
                    case 1:
                        echo '绩效';
                        break;
                    case 2:
                        echo '分享';
                        break;
                    case 3:
                        echo '额外分享';
                        break;
                    case 4:
                        echo '提现';
                        break;
                    case 5:
                        echo '注册';
                        break;
                    case 6:
                        echo '充值';
                        break;
                    case 7:
                        echo '扣除';
                        break;
                    case 8:
                        echo '赠送';
                        break;
                    case 9:
                        echo '提现失败';
                        break;
                    case 10:
                        echo '注册扣除';
                        break;
                }
                ?>
            </td>
            <td><?=$val['num']?></td>
            <td><?= ArrayHelper::getValue($note, 'note', '') ?></td>
            <td><?= date("Y-m-d H:i", $val['created_at']) ?></td>
        </tr>

    <?php } ?>
    </tbody>
</table>

<?= LinkPager::widget(['pagination' => $pages]); ?>


