<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;
use kartik\widgets\SwitchInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Goods */
/* @var $GoodsImgModel backend\models\GoodsImg */
/* @var $form yii\widgets\ActiveForm */

$this->title = '更细商品信息';
$this->params['breadcrumbs'][] = ['label' => 'Goods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$fileuploadedJs = <<<JS
    function (event, data, id, index) {
        var res = data.response;
        var ids_input = $('input[name="GoodsImg[img_path]"]');
        var new_ids = ids_input.val() +','+res.data.id;
        if(res.code == 1){
            ids_input.attr("value", new_ids);
        }
    }
JS;
?>
<div class="goods-update">

    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="goods-form">

        <?php     $form = ActiveForm::begin([
            'type' => ActiveForm::TYPE_HORIZONTAL,
            'options' => ['enctype' => 'multipart/form-data']   // important, needed for file upload
        ]);?>


        <div class="row">
            <div class="col-md-6">
                <?php
                $model->getGoodsImgs($model->id) ?>
                <?= $form->field($GoodsImgModel, 'img_path')->widget(FileInput::classname(),[
                    'options' => ['multiple' => true, 'accept' => 'image/*'],
                    'pluginOptions' => [
                        // 需要预览的文件格式
                        'previewFileType' => 'image',
                        'initialPreview' => $model->getGoodsImgs($model->id),
                        // 需要展示的图片设置，比如图片的宽度等
                        'initialPreviewConfig' => ['width' => '120px'],
                        // 是否展示预览图
                        'initialPreviewAsData' => true,
                        // 异步上传的接口地址设置
                        'uploadUrl' => Url::toRoute(['/tools/upload']),
                        // 异步上传需要携带的其他参数，比如商品id等
                        'uploadExtraData' => [
                            'type' => 'goods',
                            'is_new_record' => 0,
                            'goods_id' => 0,
                        ],
                        'uploadAsync' => true,
                        // 最少上传的文件个数限制
                        'minFileCount' => 2,
                        // 最多上传的文件个数限制
                        'maxFileCount' => 3,
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

                <?= $form->field($model, 'describe')->textarea(['rows' => 6]) ?>
            </div>

            <div class="col-md-6">

                <?= $form->field($model, 'name')->textInput(['maxlength' => 30]) ?>

                <?= $form->field($model, 'price')->textInput(['maxlength' => 10]) ?>
            </div>

        </div>

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
