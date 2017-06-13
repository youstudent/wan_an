<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use app\models\User;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\GoodsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
$this->params['breadcrumbs'][] = '商品管理列表';
?>
<div class="goods-index">

    <div class="page-header">
    
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    

<?php 
     $toolbars = [
        ['content' =>
            Html::a('<i class="glyphicon glyphicon-plus"></i>', ['goods/create'], ['type' => 'button', 'title' => '添加商品', 'class' => 'btn btn-success']) . ' ' .
           // Html::a('<i class="fa fa-file-excel-o"></i>', ['goods/parsing'], ['type' => 'button', 'title' => 'Parsing Excel ' . $this->title, 'class' => 'btn btn-danger']) . ' ' .
            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['goods/index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => '刷新']). ' '

            
        ],
        ['content' => '{dynagridFilter}{dynagridSort}{dynagrid}'],
        //'{export}',
    ];
    $panels = [
        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-folder-open"></i>  &nbsp;' . ' 商品管理列表' . '</h3>',
       // 'before' => '<div style="padding-top: 7px;"><em>* The table at the right you can pull reports & personalize</em></div>',
    ];
    $columns = [
        ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT],
            'name',
            'price',
            'describe:ntext',
        [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'vAlign' => 'middle',
            'viewOptions' => ['title' => '查看', 'data-toggle' => 'tooltip'],
            'updateOptions' => ['title' => '更新', 'data-toggle' => 'tooltip'],
            'deleteOptions' => ['title' => '删除', 'data-toggle' => 'tooltip'],
            'template'=> '{view} {update} {delete}',
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    return Html::a(Html::tag('span', '查看详情', ['class' => ""]), ['goods/view', 'id'=>$model->id], ['class' => "btn btn-xs btn-success", 'title' => '查看详情']);
                },
                'update' => function ($url, $model, $key) {
                    return Html::a(Html::tag('span', '修改', ['class' => ""]), ['goods/update', 'id'=>$model->id], ['class' => "btn btn-xs btn-success", 'title' => '修改']);
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a(Html::tag('span', '删除', ['class' => ""]), ['goods/delete', 'id'=>$model->id], ['class' => "btn btn-xs btn-success", 'title' => '删除']);
                },
            ],
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
                'options' => ['id' => 'Goods'.Yii::$app->user->identity->id] // a unique identifier is important
    ]);

    DynaGrid::end();
?>   
</div>
