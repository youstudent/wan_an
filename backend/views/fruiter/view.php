<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\Fruiter */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Fruiters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fruiter-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'member_id',
            'order_sn',
            'fruiter_name',
            'updated_at',
            'fruiter_img',
            'created_at',
            'status',
        ]]) ;?>

</div>
