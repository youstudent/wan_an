<?php

use yii\helpers\Html;
use kartik\daterange\DateRangePicker;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use app\models\Member;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\OutlineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
$this->params['breadcrumbs'][] = '退网管理列表';
?>
<div class="outline-index">

    <div class="page-header">
    
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    

<?php 
     $toolbars = [
        ['content' =>
            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['outline/index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => '刷新']). ' '
        ],
//        ['content' => '{dynagridFilter}{dynagridSort}{dynagrid}'],
        //'{export}',
    ];
    $panels = [
        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-folder-open"></i>  &nbsp;' . ' 退网管理列表' . '</h3>',
       // 'before' => '<div style="padding-top: 7px;"><em>* The table at the right you can pull reports & personalize</em></div>',
    ];
    $columns = [
        [
            'attribute' => 'vip_number',
            'filter'    => false,
        ],
            'username',
            'name',
            'mobile',
        [
            'attribute' => 'created_at',
            'label' => '退网时间',
            'value' => function ($model) {
                return date('Y-m-d H:i:s', $model->created_at);
            },
            'filter'    => DateRangePicker::widget([
                'model'         => $searchModel,
                'attribute'     => 'created_at',
                'convertFormat' => true,

            ]),
            'headerOptions' => ['width' => '300'],
        ],

        [
            'attribute' => 'updated_at',
            'label' => '注册时间',
            'value' => function ($model) {
                return date('Y-m-d H:i:s', $model->updated_at);
            },
            'filter'    => false,
        ],
        [
                'attribute' => 'status',
                'format' => 'raw',
                'value' =>  function($model){
                    return $model->status == 1 ? '<span class="label label-success radius">已确认</span>' : '<span class="label label-info radius">待确认</span>';
                },
                'filter' => [
                        0 => '待确认',
                        1 => '已确认',
                ]
        ],
        [
                'class' => 'kartik\grid\ActionColumn',
                'template'=> '{confirm}',
                'buttons' => [
                        'confirm' => function ($url, $model, $key){
                            if($model->status == 0){
                                return Html::a(Html::tag('span', '确认', ['class' => "btn btn-xs btn-danger"]), ['outline/change', 'id'=>$model->id]);
                            }
                        }
                ]
        ]
        /*[
            'class' => 'kartik\grid\CheckboxColumn',
        ],*/
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
                    'pager'=>[
                        //'options'=>['class'=>'hidden']//关闭自带分页
                        'firstPageLabel'=>'首页',
                        'prevPageLabel'=>'上一页',
                        'nextPageLabel'=>'下一页',
                        'lastPageLabel'=>'尾页',
                    ],
                    'filterModel' => $searchModel,
                    'showPageSummary' => true,
                    'floatHeader' => true,
                    'pjax' => true,
                    'panel' => $panels,
                    'toolbar' => $toolbars,
                ],
                'options' => ['id' => 'Outline'.Yii::$app->user->identity->id] // a unique identifier is important
    ]);

    DynaGrid::end();
?>   
</div>
