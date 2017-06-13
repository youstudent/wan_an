<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\Assignment */
/* @var $usernameField string */
/* @var $extraColumns string[] */

$this->title = '';
$this->params['breadcrumbs'][] = '分配';

$columns = [
    ['class' => 'yii\grid\SerialColumn'],
    $usernameField,
];
if (!empty($extraColumns)) {
    $columns = array_merge($columns, $extraColumns);
}
$columns[] = [
    'class' => 'yii\grid\ActionColumn',
    'template' => '{view}',
    'buttons' => [
        'view' => function ($url, $model, $key) {
            return Html::a(Html::tag('span', '查看详情', ['class' => ""]), ['assignment/view', 'id'=>$model->id], ['class' => "btn btn-xs btn-success", 'title' => '查看详情']);
        },
    ],
];
?>
<div class="assignment-index">

    <div class="page-header">

    </div>
    <div class="page-header">
        <h1 style="text-align: center; color: #00a0e9"><?= Html::encode('权限列表')?></h1>
    </div>

    <?php Pjax::begin(); ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]);
    ?>
    <?php Pjax::end(); ?>

</div>
