<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use backend\models\Member;
use backend\models\Bonus;
use yii\widgets\DetailView;
use kartik\daterange\DateRangePicker;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\BonussSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => '会员管理列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = '奖金详情';
?>
<div class="member-view">

    <div class="page-header">

    </div>
    <div class="page-header">
        <h1 style="text-align: center; color: #00a0e9"><?= Html::encode('奖金总览')?></h1>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'gross_income',
                'label' => '总收入',
                'value' => function ($model) {
                    return $model->getBonus(3, $model->id);
                },
            ],
            [
                'attribute' => 'gross_bonus',
                'label' => '提现',
                'value' => function ($model) {
                    return $model->getBonus(4, $model->id);
                },
            ],
            [
                'attribute' => 'a_coin',
                'label' => '金果数',
                'value' => function ($model) {
                    return $model->getBonus(1, $model->id);
                },
            ],
        ]]); ?>
    <br/>
    <?php
    $toolbars = [
        ['content' =>
            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['member/view', 'id'=>$model->id], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => '刷新']). ' '


        ],
//        ['content' => '{dynagridFilter}{dynagridSort}{dynagrid}'],
//        '{export}',
    ];
    $panels = [
        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-folder-open"></i>  &nbsp;' . ' 奖金详情列表' . '</h3>',
       // 'before' => '<div style="padding-top: 7px;"><em>* The table at the right you can pull reports & personalize</em></div>',
    ];
    $columns = [
        [
            'attribute' => 'id',
            'label' => '序号',
            'mergeHeader'=>true,
        ],
        [
            'attribute' => 'relate_username',
            'value' => function ($model) {
                return Bonus::getRelateName($model->id);
            },
            'filter'    => false,
            'mergeHeader'=>true,
        ],
        [
            'attribute' => 'created_at',
            'label' => '获得时间',
            'value' => function ($model) {
                return date('Y-m-d H:i:s', $model->created_at);
            },
            'filter'    => DateRangePicker::widget([
                'model'         => $searchModel,
                'attribute'     => 'created_at',
                'convertFormat' => true,

            ]),
            'headerOptions' => ['width' => '500'],
        ],
        [
            'attribute' => 'type',
            'value' => function($model) {
                switch ($model->type) {
                    case '1';
                        return '绩效';
                        break;
                    case '2';
                        return '分享';
                        break;
                    case '3';
                        return '额外分享';
                        break;
                    case '4';
                        return '提现';
                        break;
                    case '5';
                        return '注册奖金';
                        break;
                    default:
                        return '未知分类';
                        break;
                }
            },
            'filter' => [
                1 => '绩效',
                2 => '分享',
                3 => '额外分享',
                4 => '提现',
                5 => '注册奖金',
            ],
            'headerOptions' => ['width' => '500'],
        ],
        [
            'attribute' => 'num',
            'filter'    => false,
            'mergeHeader'=>true,
            'vAlign' => 'middle',
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
        'options' => ['id' => 'Bonus'.Yii::$app->user->identity->id] // a unique identifier is important
    ]);

    DynaGrid::end();

    ?>
</div>
