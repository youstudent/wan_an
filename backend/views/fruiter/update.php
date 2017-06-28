<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;
use kartik\widgets\SwitchInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Fruiter */
/* @var $FruiterImgModel backend\models\FruiterImg */
/* @var $form yii\widgets\ActiveForm */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => '果树管理列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = '果树补充';

$fileuploadedJs = <<<JS
    function (event, data, id, index) {
        var res = data.response;
        var ids_input = $('input[name="FruiterImg[img_path]"]');
        var new_ids = ids_input.val() +','+res.data.id;
        if(res.code == 1){
            ids_input.attr("value", new_ids);
        }
    }
JS;
?>
<div class="fruiter-update">

    <div class="page-header">

    </div>
    <div class="page-header">
        <h1 style="border-left-width: 199px;margin-left: 321px; color: #00a0e9"><?= Html::encode('果树补充')?></h1>
    </div>
    <div class="fruiter-form">

        <?php     $form = ActiveForm::begin([
            'type' => ActiveForm::TYPE_HORIZONTAL,
            'options' => ['enctype' => 'multipart/form-data']   // important, needed for file upload
        ]);?>


        <div class="row">
            <div class="col-md-6">
                <?php $model->getFruiterImgs($model->id) ?>
                <?= $form->field($model, 'fruiter_name')->textInput(['maxlength' => 20]) ?>

                <?= $form->field($FruiterImgModel, 'img_path')->widget(FileInput::classname(),[
                    'options' => ['multiple' => true, 'accept' => 'image/*'],
                    'pluginOptions' => [
                        // 需要预览的文件格式
                        'previewFileType' => 'image',
                        'initialPreview' => $model->getFruiterImgs($model->id),
                        // 需要展示的图片设置，比如图片的宽度等
                        'initialPreviewConfig' => ['width' => '120px'],
                        // 是否展示预览图
                        'initialPreviewAsData' => true,
                        // 异步上传的接口地址设置
                        'uploadUrl' => Url::toRoute(['/tools/upload']),
                        // 异步上传需要携带的其他参数，比如商品id等
                        'uploadExtraData' => [
                            'type' => 'fruiter',
                            'is_new_record' => 0,
                            'fruiter_id' => $model->id,
                        ],
                        'uploadAsync' => true,
                        // 最少上传的文件个数限制
                        'minFileCount' => 1,
                        // 最多上传的文件个数限制
                        'maxFileCount' => 4,
                        // 是否显示移除按钮，指input上面的移除按钮，非具体图片上的移除按钮
                        'showRemove' => true,
                        // 是否显示上传按钮，指input上面的上传按钮，非具体图片上的上传按钮
                        'showUpload' => true,
                        //是否显示[选择]按钮,指input上面的[选择]按钮,非具体图片上的上传按钮
                        'showBrowse' => true,
                        // 展示图片区域是否可点击选择多文件
                        'browseOnZoneClick' => false,
                        // 如果要设置具体图片上的移除、上传和展示按钮，需要设置该选项
                        'fileActionSettings' => [
                            // 设置具体图片的查看属性为false,默认为true
                            'showZoom' => false,
                            // 设置具体图片的上传属性为true,默认为true
                            'showUpload' => false,
                            // 设置具体图片的移除属性为true,默认为true
                            'showRemove' => true,
                        ],
                    ],
                    // 一些事件行为
                    'pluginEvents' => [
                        // 上传成功后的回调方法，需要的可查看data后再做具体操作，一般不需要设置
                        "fileuploaded" => $fileuploadedJs,
                    ],
                ]) ?>

            </div>

        </div>

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <?= Html::submitButton($model->isNewRecord ? '添加果树图片' : '添加果树图片', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
