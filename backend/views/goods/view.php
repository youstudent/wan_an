<?php

use backend\models\GoodsImg;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\Goods */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Goods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
                        $imgs  = $model->getGoodsImgs($model->id);
                        $html = '';
                        if(isset($imgs) && count($imgs)> 0){
                            foreach($imgs as $img){
                                $html .= Html::img($img, ['width'=> '240px', 'height'=> '240px']);
                            }
                        }
                        return $html;
                    }
            ],
            'describe:ntext',
        ]]) ;?>

</div>
