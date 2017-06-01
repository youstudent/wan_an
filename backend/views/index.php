<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = '赠送记录';
?>
<div class="give-index">

    <h1><?= Html::encode('赠送记录') ?></h1>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'member_id',
            'give_member_id',
            [
            'attribute' => 'type',
            'value' => function ($model) {
                 if ($model->type==0){
                     return '金果';
                 }else if ($model->type==1){
                     return '金种子';
                 }else{
                     return '未知类型';
                 }
        
               }
             
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    if ($model->created_at !==null){
                        return date('Y-m-d H:i:s', $model->created_at);
                    }else{
                        return '时间异常';
                    }
            
                }
            ],
            'give_coin',
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
