<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\Menu */

$this->title = '';
$this->params['breadcrumbs'][] = '菜单列表';
?>
<div class="menu-index">

    <h1>菜单列表</h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?= Html::a(Yii::t('rbac-admin', 'Create Menu'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'attribute' => 'menuParent.name',
                'filter' => Html::activeTextInput($searchModel, 'parent_name', [
                    'class' => 'form-control', 'id' => null
                ]),
                'label' => Yii::t('rbac-admin', 'Parent'),
            ],
            'route',
            'order',
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=> '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a(Html::tag('span', '查看详情', ['class' => ""]), ['menu/view', 'id'=>$model->id], ['class' => "btn btn-xs btn-success", 'title' => '查看详情']);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a(Html::tag('span', '修改', ['class' => ""]), ['menu/update', 'id'=>$model->id], ['class' => "btn btn-xs btn-success", 'title' => '修改']);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a(Html::tag('span', '删除', ['class' => ""]), ['menu/delete', 'id'=>$model->id], ['class' => "btn btn-xs btn-success", 'title' => '删除']);
                    },
                ],
            ],

        ],
    ]);
    ?>
<?php Pjax::end(); ?>

</div>
