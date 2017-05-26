<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Branner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="branner-form">

    <?php     $form = ActiveForm::begin([
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'options' => ['enctype' => 'multipart/form-data']   // important, needed for file upload
    ]);?>


        <div class="row">
       

        <div class="col-md-6">
        
            <?= $form->field($model, 'name')->textInput(['maxlength' => 30]) ?>
           
            <?=$form->field($model, 'img[]')->widget(FileInput::classname(), [
            'options' => ['multiple' => true],
                'pluginOptions' => [
                                               // 需要预览的文件格式
                    'previewFileType' => 'img',
                                               // 预览的文件
                   // 'initialPreview' => ['图片1', '图片2', '图片3'],
                                               // 需要展示的图片设置，比如图片的宽度等
                   'initialPreviewConfig' => ['width' => '120px'],
                                               // 是否展示预览图
                    'initialPreviewAsData' => true,
                                   //\yii\helpers\Url::to(['/branner/image']);   Url::toRoute(['/branner/image'])          // 异步上传的接口地址设置
                    'uploadUrl' => \yii\helpers\Url::to(['branner/image']),
                                                // 异步上传需要携带的其他参数，比如商品id等
                    'uploadAsync' => true,
                                               // 最少上传的文件个数限制
                    'minFileCount' => 1,
                                              // 最多上传的文件个数限制
                    'maxFileCount' => 3,
                                             // 是否显示移除按钮，指input上面的移除按钮，非具体图片上的移除按钮
                    'showRemove' => true,
                                             // 是否显示上传按钮，指input上面的上传按钮，非具体图片上的上传按钮
                    'showUpload' => true,
                                            //是否显示[选择]按钮,指input上面的[选择]按钮,非具体图片上的上传按钮
                    'showBrowse' => true,
                                            // 展示图片区域是否可点击选择多文件
                    'browseOnZoneClick' => true,
                                            // 如果要设置具体图片上的移除、上传和展示按钮，需要设置该选项
                    'fileActionSettings' => [
                                        // 设置具体图片的查看属性为false,默认为true
                    'showZoom' => false,
                                       // 设置具体图片的上传属性为true,默认为true
                    'showUpload' => true,
                                       // 设置具体图片的移除属性为true,默认为true
                    'showRemove' => true,
                    ],
                ],
            ]);
            ?>
            <?= $form->field($model, 'content_img')->widget('common\widgets\ueditor\Ueditor',[
                'options'=>[
                    'initialFrameWidth' => 674,
                ]
            ]) ?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
                <?= Html::submitButton($model->isNewRecord ? '添加' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

        <?php ActiveForm::end(); ?>

</div>

