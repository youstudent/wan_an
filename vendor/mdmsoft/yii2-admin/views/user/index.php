<?php

use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\grid\GridView;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel mdm\admin\models\searchs\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
$this->params['breadcrumbs'][] = '管理员列表';
?>
<div class="user-index">
    <div class="page-header">

    </div>
    <p>
        <?= Html::a(Yii::t('rbac-admin', 'Create User'), ['signup'], ['class' => 'btn btn-success ']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
            ],
            [
                'attribute' => 'username',
                'label' => '用户名',
            ],
            [
                'attribute' => 'email',
                'format' => 'email',
                'label' => '邮箱',
            ],
            [
                'attribute' => 'created_at',
                'format' => 'date',
                'label' => '创建时间',
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
                'attribute' => 'status',
                'value' => function($model) {
                    return $model->status == 0 ? '冻结' : '激活';
                },
                'filter' => [
                    0 => '冻结',
                    10 => '激活'
                ],
                'label' => '状态'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => Helper::filterActionColumn(['view', 'activate', 'delete']),
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a(Html::tag('span', '查看详情', ['class' => "btn btn-xs btn-primary"]), ['user/view', 'id'=>$model->id]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a(Html::tag('span', '删除', ['class' => "btn btn-xs btn-danger"]), ['user/delete', 'id'=>$model->id]);
                    },
                ],
            ],
            ]
        ]);
        ?>
</div>
