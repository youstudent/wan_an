<?php

use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use backend\models\Member;
use yii\grid\DataColumn;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\MemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '';
$this->params['breadcrumbs'][] = '系谱图';
$this->registerCss("
::-webkit-scrollbar {
  width: 0px;
  height: 0px;
  background-color: #F5F5F5;
}
");
?>
<div class="member-index">
<iframe src="<?php echo Yii::$app->params['admin_tree_url']; ?>" width="95%" height="1020" style="margin: 0 auto;display: block;border: none;"></iframe>
</div>
