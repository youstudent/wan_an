<?php

use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use backend\models\Member;
use backend\models\Bonus;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\BonusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $this yii\web\View */
/* @var $model backend\models\Bonus */

$this->title = '奖金详情';
$this->params['breadcrumbs'][] = ['label' => '会员管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-view">

    <h2>奖金总览</h2>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'member.gross_income',
            'member.gorss_bonus',
            'member.a_coin',
        ]]); ?>
    <br/>
    <br/>
    <h2>奖金记录列表</h2>
    <?php
    $toolbars = [
        ['content' =>
            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['member/index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => 'Reset Grid']). ' '


        ],
        ['content' => '{dynagridFilter}{dynagridSort}{dynagrid}'],
        '{export}',
    ];
    $panels = [
        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i>  ' . $this->title . '</h3>',
        'before' => '<div style="padding-top: 7px;"><em>* The table at the right you can pull reports & personalize</em></div>',
    ];
    $columns = [
//        ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT],
        'id',
        'coin_count',
        'updated_at',
        'coin_amount',
        'member_id',

        [
            'attribute' => 'created_at',
            'format' => 'datetime',
            'label' => '获得时间',
            'filter'    => DateRangePicker::widget([
                'model'         => $searchModel,
                'attribute'     => 'created_at',
                'convertFormat' => true,
                'pluginOptions' => [
                    'locale' => ['format' => 'Y-m-d'],
                ],
            ]),
        ],
        [
            'attribute' => 'type',
            'value' => function($model) {
                switch ($model->type) {
                    case '1';
                        return '绩效';
                        break;
                    case '2';
                        return '分享';
                        break;
                    case '3';
                        return '额外分享';
                        break;
                    case '4';
                        return '提现';
                        break;
                    default:
                        return '未知类型';
                        break;
                }
            },
            'filter' => [
                1 => '绩效',
                2 => '分享',
                3 => '额外分享',
                4 => '提现'
            ]
        ],
        [
            'class' => 'kartik\grid\CheckboxColumn',
        ],
    ];

    $dynagrid = DynaGrid::begin([
        'id' => 'user-grid',
        'columns' => $columns,
        'theme' => 'panel-primary',
        'showPersonalize' => true,
        'storage' => 'db',
        //'maxPageSize' =>500,
        'allowSortSetting' => true,
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'showPageSummary' => true,
            'floatHeader' => true,
            'pjax' => true,
            'panel' => $panels,
            'toolbar' => $toolbars,
        ],
        'options' => ['id' => 'Bonus'.Yii::$app->user->identity->id] // a unique identifier is important
    ]);

    DynaGrid::end();

?>
</div>
