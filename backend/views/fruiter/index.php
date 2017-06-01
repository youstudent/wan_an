<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use app\models\User;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\FruiterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Fruiters';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fruiter-index">

    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    

<?php
     $toolbars = [
        ['content' =>
            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['fruiter/index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => 'Reset Grid']). ' '

            
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
//                    'id',
            'member_id',
            'member.name',
            'order_sn',
            'fruiter_name',
        [
            'attribute' => 'fruiter_img.img_path',
            'label' => '果树图片',
            'format' => 'raw',
            'value' =>  function($model){
                $imgs  = $model->getFruiterImgs($model->id);
                $html = '';
                if(isset($imgs) && count($imgs)> 0){
                    foreach($imgs as $img){
                        $html .= Html::img($img, ['width'=> '120px', 'height'=> '120px']);
                    }
                }
                return $html;
            }
        ],
            'created_at',
        [
            'attribute' => 'status',
            'value' => function($model) {
                return $model->status == 0 ? '未补充' : '已补充';
            },
            'filter' => [
                0 => '未补充',
                1 => '已补充'
            ],
            'label' => '状态'
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'vAlign' => 'middle',
            'viewOptions' => ['title' => 'view', 'data-toggle' => 'tooltip'],
            'updateOptions' => ['title' => 'update', 'data-toggle' => 'tooltip'],
            'deleteOptions' => ['title' => 'delete', 'data-toggle' => 'tooltip'],
            'template'=> '{update} {delete}',
            'buttons' => [
                'update' => function ($url, $model, $key) {
                    return Html::a(Html::tag('span', '编辑', ['class' => ""]), ['fruiter/update', 'id'=>$model->id], ['class' => "btn btn-xs btn-success", 'title' => '编辑']);
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a(Html::tag('span', '删除', ['class' => ""]), ['fruiter/delete', 'id'=>$model->id], ['class' => "btn btn-xs btn-success", 'title' => '删除']);
                },
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
                'options' => ['id' => 'Fruiter'.Yii::$app->user->identity->id] // a unique identifier is important
    ]);

    DynaGrid::end();
?>   
</div>
