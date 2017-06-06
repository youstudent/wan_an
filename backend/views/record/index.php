<?php
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use backend\models\User;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
$this->params['breadcrumbs'][] = '财务管理列表';
?>

<div class="page-header">

</div>
<ul class="nav nav-tabs">
    <li class="dropdown"><a href="<?= \yii\helpers\Url::to(['record/index?status=0']) ?>">待审核</a></li>
    <li class="dropdown"><a href="<?= \yii\helpers\Url::to(['record/index?status=1']) ?>">已审核</a></li>
</ul>
<div class="record-index">
    <div class="page-header">
    
    </div>
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    
    <?php
    $toolbars = [
        ['content' =>
            // Html::a('<i class="glyphicon glyphicon-plus"></i>', ['record/create'], ['type' => 'button', 'title' => '添加数据 ' ,'class' => 'btn btn-success']) . ' ' .
            // Html::a('<i class="fa fa-file-excel-o"></i>', ['record/parsing'], ['type' => 'button', 'title' => 'Parsing Excel ' . $this->title, 'class' => 'btn btn-danger']) . ' ' .
             Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['record/index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => '刷新']). ' '


         ],
        ['content' => '{dynagridFilter}{dynagridSort}{dynagrid}'],
        //'{export}',
    ];
    $panels = [
        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-folder-open"></i>'." &nbsp; 财务管理列表". '</h3>',
    
    ];
    $columns = [
        ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT],
        
        'member_id',
        [
            'label' => '会员名',
            'attribute' => 'mamber_name',
            'value' => 'member.name'
        
        ],
        'coin',
        'member.mobile',
        'member.deposit_bank',
        'member.bank_account',
        [
            'attribute' => 'created_at',
            'label' => '申请时间',
            'value' => function ($model) {
                return date('Y-m-d H:i:s', $model->created_at);
            },
            'filter'    => DateRangePicker::widget([
                'model'         => $searchModel,
                'attribute'     => 'created_at',
                'convertFormat' => true,
        
            ]),
        ],
        [
            'attribute' => 'updated_at',
            'value' => function ($model) {
                if ($model->updated_at !==null){
                    return date('Y-m-d H:i:s', $model->updated_at);
                }else{
                    return '数据还未处理';
                }
               
            }
        ],
        [
            'attribute' => 'status',
            'value' => function ($model) {
                switch ($model->status) {
                    case 0:
                        return '待处理';
                        break;
                    case 1:
                        return '已通过';
                        break;
                    case 2:
                        return '已拒绝';
                        break;
                    
                }
                
            },
            'filter' => [
                1 => '已通过',
                2 => '已拒绝'
            ]
        
        
        ],
        
        ['class' => 'kartik\grid\ActionColumn',
            //'dropdown' => false,
            //'vAlign' => 'middle',
            //'viewOptions' => ['title' => '查看', 'data-toggle' => 'tooltip'],
            //'updateOptions' => ['title' => '通过'],
            //'deleteOptions' => ['title' => '拒绝'],
            
            'template' => '{update} {delete}',
            'buttons' => [
                'update' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', '通过'),
                        'aria-label' => Yii::t('yii', '通过'),
                        'data-pjax' => '0',
                    ];
                    if ($model->status == 0) {
                        return Html::a('通过', $url, $options);
                    }
                    
                },
                'delete' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', '拒绝'),
                        'aria-label' => Yii::t('yii', '拒绝'),
                        'data-pjax' => '0',
                    ];
                    if ($model->status == 0) {
                        return Html::a('拒绝', $url, $options);
                    }
                    
                },
            
            ]
        
        
        ],
        
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
            'filterModel' => $searchModel,
            'showPageSummary' => true,
            'floatHeader' => true,
            'pjax' => true,
            'panel' => $panels,
            'toolbar' => $toolbars,
        ],
        'options' => ['id' => 'Record' . Yii::$app->user->identity->id] // a unique identifier is important
    ]);
    
    DynaGrid::end();
    ?>
</div>
