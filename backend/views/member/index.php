<?php

use common\components\Helper;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use backend\models\Member;
use yii\grid\DataColumn;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\MemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
$this->params['breadcrumbs'][] = '会员管理列表';
?>
<div class="member-index">

    <div class="page-header">

    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


<?php
     $toolbars = [
        ['content' =>
           // Html::a('<i class="fa fa-file-excel-o"></i>', ['member/parsing'], ['type' => 'button', 'title' => '刷新 ' . $this->title, 'class' => 'btn btn-danger']) . ' ' .
            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['member/index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => '刷新']). ' '

        ],
//        ['content' => '{dynagridFilter}{dynagridSort}{dynagrid}'],
        //'{export}',
    ];
    $panels = [
        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-folder-open"></i>  &nbsp;' . ' 会员管理列表' . '</h3>',
        //'before' => '<div style="padding-top: 7px;"><em>* The table at the right you can pull reports & personalize</em></div>',
    ];

    $columns = [
        ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT],
        [
            'attribute' => 'vip_number',
            'label' => '会员ID',
            'headerOptions' => ['width' => '100'],
        ],
        [
            'attribute' => 'mobile',
            'headerOptions' => ['width' => '150'],
        ],
        [
            'attribute' => 'created_at',
            'label' => '注册时间',
            'value' => function ($model) {
                return date('Y-m-d H:i:s', $model->created_at);
            },
            'filter'    => DateRangePicker::widget([
                'model'         => $searchModel,
                'attribute'     => 'created_at',
                'convertFormat' => true,
                'pluginOptions' => [
                    'locale' => ['format' => 'Y-m-d'],
                ],
            ]),
            'headerOptions' => ['width' => '200'],

        ],
        [
            'attribute' => 'name',
            'filter'    => false,
            'mergeHeader'=>true,
        ],
        [
            'attribute' => 'parent_id',
            'filter'    => false,
            'mergeHeader'=>true,
            'value' => function($model) {
                return Helper::memberIdToVipNumber($model->parent_id);
            }
        ],
        [
            'attribute' => 'a_coin',
            'label' => '金果数',
            'value' => function ($model) {
                return $model->getBonus(1, $model->id);
            },
            'filter'    => false,
            'mergeHeader'=>true,
        ],
        [
            'attribute' => 'b_coin',
            'label' => '金种子数',
            'value' => function ($model) {
                return $model->getBonus(2, $model->id);
            },
            'filter'    => false,
            'mergeHeader'=>true,
        ],
        [
            'attribute' => 'group_num',
            'label' => '区数量',
            'value' => function ($model) {
                return $model->getChild(2, $model->id);
            },
            'filter'    => false,
            'mergeHeader'=>true,
        ],
        [
            'attribute' => 'child',
            'label' => '挂靠数',
            'value' => function ($model) {
                return $model->getChild(3, $model->id);
            },
            'filter'    => false,
            'mergeHeader'=>true,
        ],
        [
            'attribute' => 'last_login_time',
            'label' => '最后登录时间',
            'value' => function ($model) {
                return date('Y-m-d H:i:s', $model->created_at);
            },
            'filter'    => false,
            'mergeHeader'=>true,
        ],
        [
            'attribute' => 'last_ip',
            'label' => '最后登录地址',
            'filter'    => false,
            'mergeHeader'=>true,
        ],
        [
            'attribute' => 'status',
            'value' => function($model) {
                switch ($model-> status) {
                    case '0';
                        return '<span class="label bg-primary">冻结</span>';
                        break;
                    case '1';
                        return '<span class="label bg-primary">正常</span>';
                        break;
                    case '2';
                        return '<span class="label bg-primary">已退网</span>';
                        break;
                    default:
                        return '未知状态';
                        break;
                }
            },
            'format'=>'raw',
            'filter'    => false,
            'mergeHeader'=>true,
//            'filter' => [
//                0 => '已冻结',
//                1 => '正常',
//                2 => '已退网'
//            ]
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'vAlign' => 'middle',
            'viewOptions' => ['title' => '奖金详情', 'data-toggle' => 'tooltip'],
            'updateOptions' => ['title' => '修改信息', 'data-toggle' => 'tooltip'],
            'deleteOptions' => ['title' => '冻结', 'data-toggle' => 'tooltip'],
            'template'=> '{view} {update} {change} {unchange}',
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    return Html::a(Html::tag('span', '奖金详情', ['class' => "btn btn-xs btn-primary"]), ['member/view', 'id'=>$model->id]);
                },
                'update' => function ($url, $model, $key) {
                    return Html::a(Html::tag('span', '修改', ['class' => "btn btn-xs btn-success"]), ['member/update', 'id'=>$model->id]);
                },
                'change' => function ($url, $model, $key) {
                    if ($model->status == 0) {
                        return '';
                    }
                    if ($model->status == 2) {
                        return '';
                    }
                    return Html::a(Html::tag('span', '冻结', ['class' => "btn btn-xs btn-danger"]), ['member/change', 'id'=>$model->id, 'state' => 0]);
                },
                'unchange' =>  function ($url, $model, $key) {
                    if ($model->status == 1) {
                        return '';
                    }
                    if ($model->status == 2) {
                        return '';
                    }
                    return Html::a(Html::tag('span', '解冻', ['class' => "btn btn-xs btn-info"]), ['member/change', 'id'=>$model->id, 'state' => 1]);
                },
            ]
        ],
        [
//            'class' => 'kartik\grid\CheckboxColumn',
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
                'options' => ['id' => 'Member'.Yii::$app->user->identity->id] // a unique identifier is important
    ]);

    DynaGrid::end();
?>
</div>
