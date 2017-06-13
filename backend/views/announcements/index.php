<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use api\models\User;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
$this->params['breadcrumbs'][] = '公告管理列表';
?>
<div class="announcements-index">

    <div class="page-header">
    
    </div>
    
<?php 
     $toolbars = [
        ['content' =>
            Html::a('<i class="glyphicon glyphicon-plus"></i>', ['announcements/create'], ['type' => 'button', 'title' => '添加公告 ', 'class' => 'btn btn-success']) . ' ' .
            //Html::a('<i class="fa fa-file-excel-o"></i>', ['announcements/parsing'], ['type' => 'button', 'title' => 'Parsing Excel ' . $this->title, 'class' => 'btn btn-danger']) . ' ' .
            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['announcements/index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => '刷新']). ' '

            
        ],
//        ['content' => '{dynagridFilter}{dynagridSort}{dynagrid}'],
        //'{export}',
    ];
    $panels = [
        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-folder-open"></i>'." &nbsp; 公告管理列表". '</h3>',
        /*'before' => '<div style="padding-top: 7px;"><em>* The table at the right you can pull reports & personalize</em></div>',*/
    ];
    $columns = [
        ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT],
                    'id',
            'title',
            'author',
        [
            'attribute' => 'content',
            'value' => function ($model) {
                return strip_tags($model->content);
            }
        ],
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
            'template'=> '{view} {update} {delete}',
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    return Html::a(Html::tag('span', '查看详情', ['class' => "btn btn-xs btn-primary"]), ['announcements/view', 'id'=>$model->id]);
                },
                'update' => function ($url, $model, $key) {
                    return Html::a(Html::tag('span', '修改', ['class' => "btn btn-xs btn-success"]), ['announcements/update', 'id'=>$model->id]);
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a(Html::tag('span', '删除', ['class' => "btn btn-xs btn-danger del"]), ['announcements/delete', 'id'=>$model->id]);
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
                'options' => ['id' => 'Announcements'.Yii::$app->user->identity->id] // a unique identifier is important
    ]);

    DynaGrid::end();
   $js =<<<JS
       $(function () {
            $('.del').click(function () {
                var form = $(this);
                if (confirm("确定要删除吗?")) {
                    $.ajax({
                        url: form.attr('action'),
                        type: 'post',
                        data: form.serialize()
                    });
                    if (data == 1){
                        $(tThis).parent().parent().remove();
                        alert('删除成功')
                    }else{
                        alert('删除失败')
                    }
                }
                return false;
            })
        });

JS;
$this->registerJs($js);
?>   
</div>

