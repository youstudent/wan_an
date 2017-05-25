<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\Member */

$this->title = '奖金详情';
$this->params['breadcrumbs'][] = ['label' => '会员管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-view">

    <h2>奖金总览</h2>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'site',
            'parent_id',
        ]]); ?>
    <br/>
    <br/>
    <h2>奖金记录列表</h2>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'site',
            'parent_id',
            'name',
            'password',
            'mobile',
            'deposit_bank',
            'bank_account',
            'address',
            'group_num',
            'child_num',
            'a_coin',
            'b_coin',
            'gross_income',
            'gorss_bonus',
            'last_login_time:datetime',
            'status',
            'created_at',
            'updated_at',
        ]]); ?>

</div>
