<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class Upload extends Model
{
    public $img_path;

    /**
     * 初始化上传
     * @param $params
     */
    public function upload($params)
    {
       $type = $params['type'];
       switch($type){
           case 'goods':
               return $this->uploadGoodsImgs($params);
               break;
       }
    }

    public function uploadGoodsImgs($params)
    {
        $model = new GoodsImg();
        $this->img_path = UploadedFile::getInstance($model, 'img_path');
        if(isset($this->img_path)){
            //TODO:: 这里的savePath获取的是错误的，要修复
            $model->img_path = $this->getSavePath('goods', $this->img_path->name);
            $this->img_path->saveAs($model->img_path);
            $model->save();
            return $model->id;
        }
        return null;
    }

    protected function getSavePath($type, $filename)
    {
        $save_path = '';
        switch($type){
            case 'goods':
                $save_path = Yii::$app->params['upload_goods_path'] . DIRECTORY_SEPARATOR . date('Y-m-d') . DIRECTORY_SEPARATOR . sha1($filename . time()) .'.'. $this->getFileExtension($filename);
                break;
        }
        if(!empty($save_path)){
            $save_dir = dirname($save_path);
            if(!is_dir($save_dir)){
                mkdir($save_dir, 0755);

            }
        }
        return $save_path;
    }
    protected function getFileExtension($filename) {
        return strtolower(pathinfo($filename)['extension']);
    }

}