<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use api\models\User;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\GiveSearchs */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
$this->params['breadcrumbs'][] = '赠送记录';
?>
<div class="give-index">

    <div class="page-header">
    
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    

<?php 
     $toolbars = [
        ['content' =>
           // Html::a('<i class="glyphicon glyphicon-plus"></i>', ['give/create'], ['type' => 'button', 'title' => 'Add ' . $this->title, 'class' => 'btn btn-success']) . ' ' .
           // Html::a('<i class="fa fa-file-excel-o"></i>', ['give/parsing'], ['type' => 'button', 'title' => 'Parsing Excel ' . $this->title, 'class' => 'btn btn-danger']) . ' ' .
            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['give/index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => '刷新']). ' '

            
        ],
//        ['content' => '{dynagridFilter}{dynagridSort}{dynagrid}'],
       // '{export}',
    ];
    $panels = [
        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-folder-open"></i>  &nbsp;' . ' 赠送记录列表' . '</h3>',
        //'before' => '<div style="padding-top: 7px;"><em>* The table at the right you can pull reports & personalize</em></div>',
    ];
    $columns = [
//        ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT],
        [
            'attribute' => 'id',
            'mergeHeader'=>true,
        ],
        [
            'attribute' => 'give_username',
            'label' => '赠送人',
            'value' => 'give.username',
            'headerOptions' => ['width'=>'150px']
        ],
        [
            'attribute' => 'gain_username',
            'label' => '被赠人',
            'headerOptions' => ['width'=>'150px'],
            'value' => 'gain.username'
        ],
        [
            'attribute' => 'type',
            'value' => function ($model) {
                if ($model->type==1){
                    return '金果';
                }else if ($model->type==2){
                    return '金种子';
                }else{
                    return '未知类型';
                }
            
            },
            'mergeHeader'=>true,
    
        ],
        [
            'attribute' => 'created_at',
            'value' => function ($model) {
                if ($model->created_at !==null){
                    return date('Y-m-d H:i:s', $model->created_at);
                }else{
                    return '时间异常';
                }
            
            },
            'mergeHeader'=>true,
        ],
        [
            'attribute' => 'give_coin',
            'mergeHeader'=>true,
        ],
        /*[
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'vAlign' => 'middle',
            'viewOptions' => ['title' => 'view', 'data-toggle' => 'tooltip'],
            'updateOptions' => ['title' => 'update', 'data-toggle' => 'tooltip'],
            'deleteOptions' => ['title' => 'delete', 'data-toggle' => 'tooltip'],
        ],*/
        [
            //'class' => 'kartik\grid\CheckboxColumn',
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
                'options' => ['id' => 'Give'.Yii::$app->user->identity->id] // a unique identifier is important
    ]);

    DynaGrid::end();
?>   
</div>
