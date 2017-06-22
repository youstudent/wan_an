<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use app\models\User;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\Order */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '订单表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    

<?php 
     $toolbars = [
        ['content' =>
//            Html::a('<i class="glyphicon glyphicon-plus"></i>', ['order/create'], ['type' => 'button', 'title' => 'Add ' . $this->title, 'class' => 'btn btn-success']) . ' ' .
//            Html::a('<i class="fa fa-file-excel-o"></i>', ['order/parsing'], ['type' => 'button', 'title' => 'Parsing Excel ' . $this->title, 'class' => 'btn btn-danger']) . ' ' .
            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['order/index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => 'Reset Grid']). ' '


        ],
        //['content' => '{dynagridFilter}{dynagridSort}{dynagrid}'],
        //'{export}',
    ];
    $panels = [
        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> </h3>',
        'before' => '<div style="padding-top: 7px;"><em></em></div>',
    ];
    $columns = [
        ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT],
            'order_sn',
            [
                    'attribute' => 'member_id',
                    'label' => '购买人',
                    'value' => 'member.name'
            ],
            'name',
            'price',
            'status',
        [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'vAlign' => 'middle',
            'viewOptions' => ['title' => 'view', 'data-toggle' => 'tooltip'],
            'updateOptions' => ['title' => 'update', 'data-toggle' => 'tooltip'],
            'deleteOptions' => ['title' => 'delete', 'data-toggle' => 'tooltip'],
        ],
//        [
//            'class' => 'kartik\grid\CheckboxColumn',
//        ],
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
                'options' => ['id' => 'Order'.Yii::$app->user->identity->id] // a unique identifier is important
    ]);

    DynaGrid::end();
?>   
</div>
