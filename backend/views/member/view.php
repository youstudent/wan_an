<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use backend\models\Member;
use backend\models\Bonus;
use yii\widgets\DetailView;
use kartik\daterange\DateRangePicker;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\BonussSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '奖金详情';
$this->params['breadcrumbs'][] = ['label' => '会员管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-view">

    <h2>奖金总览</h2>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'gross_income',
            'gross_bonus',
            'a_coin',
        ]]); ?>
    <br/>
    <br/>
    <h2>奖金详情</h2>
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
        'id',
        [
            'attribute' => 'created_at',
            'label' => '获得时间',
            'value' => function ($model) {
                return date('Y-m-d H:i:s', $model->created_at);
            },
            'filter'    => DateRangePicker::widget([
                'model'         => $searchModel,
                'attribute'     => 'created_at',
                'convertFormat' => true,

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
                    case '5';
                        return '注册奖金';
                        break;
                    default:
                        return '未知分类';
                        break;
                }
            },
            'filter' => [
                1 => '绩效',
                2 => '分享',
                3 => '额外分享',
                4 => '提现',
                5 => '注册奖金'
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
