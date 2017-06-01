<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\Announcements */
$this->title = '';
//$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '公告管理列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = '公告管理详情';
?>
<div class="announcements-view">
    <div class="page-header">

    </div>
    <div class="page-header">
        <h1 style="text-align: center; color: #00a0e9"><?= Html::encode('商品添加')?></h1>
    </div>
    <div class="page-header">

    </div>
    <p>
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '确认要删除吗?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
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
                'attribute' => 'content',
                'label' => '文本',
                'format' => 'raw',
                'value' =>  function($model){
                    return $model->content;
                },
            ],
        ]]) ;?>

</div>
