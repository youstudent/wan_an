<?php

use backend\models\GoodsImg;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\Goods */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => '商品管理列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = '商品详情';
?>
<div class="goods-view">
    <div class="page-header">

    </div>
    <div class="page-header">
        <h1 style="text-align: center; color: #00a0e9"><?= Html::encode('商品详情')?></h1>
    </div>

    <p>
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'price',
            [
                    'attribute' => 'goods_imgs',
                    'label' => '商品图',
                    'format' => 'raw',
                    'value' =>  function($model){
                        $img = GoodsImg::findOne(['goods_id'=>$model->id])->img_path;
                        if($img){
                            return Html::img(Yii::$app->params['img_domain'].$img, ['width'=> '240px', 'height'=> '240px']);
                        }
                        return  '';
                    }
            ],
            'describe:ntext',
        ]]) ;?>

</div>
