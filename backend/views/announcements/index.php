<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use app\models\User;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '公告管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="announcements-index">

    <!--<div class="page-header">
        <h1><?/*= Html::encode($this->title) */?></h1>
    </div>-->
    
<?php 
     $toolbars = [
        ['content' =>
            Html::a('<i class="glyphicon glyphicon-plus"></i>', ['announcements/create'], ['type' => 'button', 'title' => '添加公告 ', 'class' => 'btn btn-success']) . ' ' .
            //Html::a('<i class="fa fa-file-excel-o"></i>', ['announcements/parsing'], ['type' => 'button', 'title' => 'Parsing Excel ' . $this->title, 'class' => 'btn btn-danger']) . ' ' .
            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['announcements/index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => '刷新']). ' '

            
        ],
        ['content' => '{dynagridFilter}{dynagridSort}{dynagrid}'],
        '{export}',
    ];
    $panels = [
        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i>  ' . $this->title . '</h3>',
        /*'before' => '<div style="padding-top: 7px;"><em>* The table at the right you can pull reports & personalize</em></div>',*/
    ];
    $columns = [
        ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT],
                    'id',
            'title',
            'author',
            'content:ntext',
        [
            'attribute' => 'created_at',
            'value' => function ($model) {
                if ($model->created_at !==0){
                    return date('Y-m-d H:i:s', $model->created_at);
                }else{
                    return '未知时间';
                }
            
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
            'updateOptions' => ['title' => '修改公告', 'data-toggle' => 'tooltip'],
            'deleteOptions' => ['title' => '删除公告', 'data-toggle' => 'tooltip'],
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
                    'showPageSummary' => true,
                    'floatHeader' => true,
                    'pjax' => true,
                    'panel' => $panels,
                    'toolbar' => $toolbars,
                ],
                'options' => ['id' => 'Announcements'.Yii::$app->user->identity->id] // a unique identifier is important
    ]);

    DynaGrid::end();
?>   
</div>
