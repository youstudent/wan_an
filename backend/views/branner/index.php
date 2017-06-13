<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use app\models\User;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
$this->params['breadcrumbs'][] = '广告管理列表';
?>
<div class="branner-index">

    <div class="page-header">

    </div>
    

<?php 
     $toolbars = [
        ['content' =>
            Html::a('<i class="glyphicon glyphicon-plus"></i>', ['branner/create'], ['type' => 'button', 'title' => '添加广告位 ', 'class' => 'btn btn-success']) . ' ' .
            //Html::a('<i class="fa fa-file-excel-o"></i>', ['branner/parsing'], ['type' => 'button', 'title' => 'Parsing Excel ' . $this->title, 'class' => 'btn btn-danger']) . ' ' .
            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['branner/index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' =>'刷新']). ' '

            
        ],
//        ['content' => '{dynagridFilter}{dynagridSort}{dynagrid}'],
       // '{export}',
    ];
    $panels = [
        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-folder-open"></i>'." &nbsp; 广告管理列表". '</h3>',
        //'before' => '<div style="padding-top: 7px;"><em>* The table at the right you can pull reports & personalize</em></div>',
    ];
    $columns = [
        ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT],
             'id',
            'name',
        [
            'attribute' => 'img',
            'label' => '轮播图片',
            'format' => 'raw',
            'value' =>  function($model){
             if ($model->img){
                 return  Html::img(Yii::$app->params['img_domain'].$model->img, ['width'=> '40px', 'height'=> '40px']);
             }
                 return '还未上传图片';
                    
             }
        ],
        [
            'attribute'=>'status',
            'value'=>function($model){
                if ($model->status==1){
                    return '启用';
                }else{
                    return '禁用';
                }
            }
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'vAlign' => 'middle',
            'viewOptions' => ['title' => '查看详情', 'data-toggle' => 'tooltip'],
            'updateOptions' => ['title' => '修改', 'data-toggle' => 'tooltip'],
            'deleteOptions' => ['title' => '删除', 'data-toggle' => 'tooltip'],
            'template'=> '{view} {update} {delete}',
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    return Html::a(Html::tag('span', '查看详情', ['class' => "btn btn-xs btn-primary"]), ['branner/view', 'id'=>$model->id]);
                },
                'update' => function ($url, $model, $key) {
                    return Html::a(Html::tag('span', '修改', ['class' => "btn btn-xs btn-success"]), ['branner/update', 'id'=>$model->id]);
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a(Html::tag('span', '删除', ['class' => "btn btn-xs btn-danger"]), ['branner/delete', 'id'=>$model->id]);
                },
            ],
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
                    'showPageSummary' => true,
                    'floatHeader' => true,
                    'pjax' => true,
                    'panel' => $panels,
                    'toolbar' => $toolbars,
                ],
                'options' => ['id' => 'Branner'.Yii::$app->user->identity->id] // a unique identifier is important
    ]);

    DynaGrid::end();
?>   
</div>
