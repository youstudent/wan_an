<?php

use yii\helpers\Html;
use yii\grid\GridView;
use mdm\admin\components\RouteRule;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\AuthItem */
/* @var $context mdm\admin\components\ItemController */

$context = $this->context;
$labels = $context->labels();
$this->title = Yii::t('rbac-admin', $labels['Items']);
$this->params['breadcrumbs'][] = $this->title;

$rules = array_keys(Yii::$app->getAuthManager()->getRules());
$rules = array_combine($rules, $rules);
unset($rules[RouteRule::RULE_NAME]);
?>
<div class="role-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('rbac-admin', 'Create ' . $labels['Item']), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'name',
                'label' => Yii::t('rbac-admin', 'Name'),
            ],
            [
                'attribute' => 'ruleName',
                'label' => Yii::t('rbac-admin', 'Rule Name'),
                'filter' => $rules
            ],
            [
                'attribute' => 'description',
                'label' => Yii::t('rbac-admin', 'Description'),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=> '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a(Html::tag('span', '查看详情', ['class' => ""]), ['role/view', 'id'=>$model->name], ['class' => "btn btn-xs btn-success", 'title' => '查看详情']);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a(Html::tag('span', '修改', ['class' => ""]), ['role/update', 'id'=>$model->name], ['class' => "btn btn-xs btn-success", 'title' => '修改']);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a(Html::tag('span', '删除', ['class' => ""]), ['role/delete', 'id'=>$model->name], ['class' => "btn btn-xs btn-success", 'title' => '删除']);
                    },
                ],
            ],
        ],
    ])
    ?>

</div>
