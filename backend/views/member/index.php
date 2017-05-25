<?php

use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use app\models\User;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\MemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '会员管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-index">

    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


<?php 
     $toolbars = [
        ['content' =>
            Html::a('<i class="fa fa-file-excel-o"></i>', ['member/parsing'], ['type' => 'button', 'title' => 'Parsing Excel ' . $this->title, 'class' => 'btn btn-danger']) . ' ' .
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
            'name',
            'parent_id',
        [
            'attribute' => 'created_at',
            'format' => 'datetime',
            'label' => '注册时间',
            'filter'    => DateRangePicker::widget([
                'model'         => $searchModel,
                'attribute'     => 'created_at',
                'convertFormat' => true,
                'pluginOptions' => [
                    'locale' => ['format' => 'Y-m-d'],
                ],
            ]),
        ],
            'a_coin',
            'b_coin',
            'group_num',
            'child_num',
            'last_login_time:datetime',
        [
            'attribute' => 'status',
            'value' => function($model) {
                return $model->status == 0 ? '冻结' : '正常';
            },
            'filter' => [
                0 => '已冻结',
                1 => '正常'
            ]
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'vAlign' => 'middle',
            'viewOptions' => ['title' => '奖金详情', 'data-toggle' => 'tooltip'],
            'updateOptions' => ['title' => '修改信息', 'data-toggle' => 'tooltip'],
            'deleteOptions' => ['title' => '删除', 'data-toggle' => 'tooltip'],
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
                'options' => ['id' => 'Member'.Yii::$app->user->identity->id] // a unique identifier is important
    ]);

    DynaGrid::end();
?>   
</div>
