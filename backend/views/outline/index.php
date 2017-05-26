<?php

use yii\helpers\Html;
use kartik\daterange\DateRangePicker;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use app\models\Member;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\OutlineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '退网管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="outline-index">

    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    

<?php 
     $toolbars = [
        ['content' =>
            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['outline/index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => 'Reset Grid']). ' '
        ],
        ['content' => '{dynagridFilter}{dynagridSort}{dynagrid}'],
        '{export}',
    ];
    $panels = [
        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i>  ' . $this->title . '</h3>',
        'before' => '<div style="padding-top: 7px;"><em>* The table at the right you can pull reports & personalize</em></div>',
    ];
    $columns = [
        ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT],
            'member_id',
            'member.name',
            'member.mobile',
        [
            'attribute' => 'created_at',
            'format' => 'datetime',
            'label' => '退网时间',
            'filter'    => DateRangePicker::widget([
                'model'         => $searchModel,
                'attribute'     => 'updated_at',
                'convertFormat' => true,
                'pluginOptions' => [
                    'locale' => ['format' => 'Y-m-d'],
                ],
            ]),
        ],
        [
            'attribute' => 'created_at',
            'format' => 'datetime',
            'label' => '注册时间',
            'filter'    => DateRangePicker::widget([
                'model'         => $searchModel,
                'attribute'     => 'created_at',
                'convertFormat' => true,
                'pluginOptions' => [
                    'locale' => ['format' => 'Y-m-d'],
                ],
            ]),
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
                'options' => ['id' => 'Outline'.Yii::$app->user->identity->id] // a unique identifier is important
    ]);

    DynaGrid::end();
?>   
</div>
