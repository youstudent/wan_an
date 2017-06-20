<?php

use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use backend\models\Member;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\DepositSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '充值记录';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deposit-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    

<?php 
     $toolbars = [
        ['content' =>
            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['deposit/index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => 'Reset Grid']). ' '

            
        ],
//        ['content' => '{dynagridFilter}{dynagridSort}{dynagrid}'],
//        '{export}',
    ];
    $panels = [
        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i>  ' . $this->title . '</h3>',
//        'before' => '<div style="padding-top: 7px;"><em>* The table at the right you can pull reports & personalize</em></div>',
    ];
    $columns = [
        ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT],
        [
            'attribute' => 'member_username',
            'value' => 'member.username',
            'label' => '会员名',
            'headerOptions' => ['width' => '200'],
        ],
        [
            'attribute' => 'created_at',
            'label' => '充值时间',
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
            'attribute' => 'type',
            'value' => function($model) {
                switch ($model-> type) {
                    case '1';
                        return '金果';
                        break;
                    case '2';
                        return '金种子';
                        break;
                }
            },
            'filter' => [
                1 => '金果',
                2 => '金种子'
            ],
            'headerOptions' => ['width' => '200'],
        ],
        [
            'attribute' => 'num',
            'label' => '充值数量',
            'filter'    => false,
            'mergeHeader'=>true,
            'headerOptions' => ['width' => '200'],
        ],
        [
            'attribute' => 'operation',
            'label' => '操作类型',
            'value' => function($model) {
                switch ($model-> operation) {
                    case '1';
                        return '充值';
                        break;
                    case '2';
                        return '扣除';
                        break;
                }
            },
//            'filter' => [
//                1 => '充值',
//                2 => '扣除'
//            ]
            'mergeHeader'=>true,
            'headerOptions' => ['width' => '200'],
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
                'options' => ['id' => 'Deposit'.Yii::$app->user->identity->id] // a unique identifier is important
    ]);

    DynaGrid::end();
?>   
</div>
