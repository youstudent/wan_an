<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;
use kartik\widgets\SwitchInput;
// backend\models\RecordController 'excel'
$this->title='Parsing / Upload' ;
$this->params['breadcrumbs'][] = ['label' => ' backend\models\Record', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

/* @var $this yii\web\View */
/* @var $model backend\models\Operator */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="sintret-update">

    <div class="page-header">
        <h1>Parsing Excel backend\models\Record</h1>
    </div>


    <div class="backend\models\Record-form">


        <div class="backend\models\Record-form">
            <?php
            $form = ActiveForm::begin([
                        'type' => ActiveForm::TYPE_HORIZONTAL,
                        'options' => ['enctype' => 'multipart/form-data']   // important, needed for file upload
            ]);
            ?>

            <div class="row">
                <div class="col-md-10">
                    <?php
                    echo $form->field($model, 'fileori')->widget(FileInput::classname(), [
                        'options' => ['accept' => '.xls'],
                    ]);
                    ?>

                </div>


            </div>

            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <?=                    Html::submitButton('Upload ', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])
                    ?>
                </div>
            </div>
            <div class="notifications"></div>
            
            <?php
            ActiveForm::end();
            ?>

        </div>

    </div>
    <hr>
    <div class="row">
        <div class="col-md-10">
            Format Sample : <a href="<?php echo Yii::$app->urlManager->createUrl('record/sample');?>">record.xls</a>
        </div>

    </div>
</div>

<?php 
if(Yii::$app->session->get($log)){
$this->registerJs('$(document).ready(function(){ $.ajax({
        type:"POST",
        url:"' . Yii::$app->urlManager->createUrl([$route,'id'=>Yii::$app->session->get($log)]) . '",
        beforeSend:function(){ $("#notifications").html("'.yii\helpers\Url::to("@web/img/loadingAnimation.gif").'");},
        success:function(html){
            $(".notifications").html(html);
        }
    });});');
 } 
 Yii::$app->session->set($log,NULL);
?> 
