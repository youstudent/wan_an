<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\Branner */
$this->title='';
$this->params['breadcrumbs'][] = ['label' => '广告管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = '详情';
?>
<div class="branner-view">

    <div class="page-header">

    </div>
    <div class="page-header">
        <h1 style="text-align: center; color: #00a0e9"><?= Html::encode('广告详情')?></h1>
    </div>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '确认要删除吗??',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
               [
            'attribute' => 'img',
            'label' => '轮播图片',
            'format' => 'raw',
            'value' =>  function($model){
                if ($model->img){
                    return  Html::img($model->http.'/'.$model->img, ['width'=> '100px', 'height'=> '100px']);
                }
                return '还未上传图片';
        
            },
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
