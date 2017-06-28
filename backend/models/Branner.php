<?php

namespace backend\models;

use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;


/**
 * This is the model class for table "{{%branner}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $img
 * @property string $content_img
 */
class Branner extends \yii\db\ActiveRecord
{
    public static $status_options = [1 => '启用', 0 => '禁用'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%branner}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'content', 'status'], 'required'],
            [['name'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'img' => '图片',
            'name' => '名称',
            'status' => '状态',
            'content' => '文本',
        ];
    }


    public function addBanner($post)
    {
        if (!$this->load($post)) {
            return null;
        }

        if (!$this->validate()) {
            return null;
        }

        $this->img = UploadedFile::getInstance($this, 'img');
        if ($this->img) {
            $path = '/public/upload/branner_imgs/';
            $uniqid = uniqid();
            $save_path = '/public/upload/branner_imgs/' . $uniqid  . '.' . $this->img->extension; ;
            $path = $path . $uniqid . '.' . $this->img->extension;
            $path_dir = dirname(Yii::getAlias('@webroot') . $path);
            if (!is_dir($path_dir)) {
                FileHelper::createDirectory($path, $mode = 0775, $recursive = true);
            }
            $this->img->saveAs(Yii::getAlias('@webroot') . $path);
            $this->img = $save_path;
        }
        $this->save();
        return $this;
    }

    public function updateBanner($post)
    {
        if (!$this->load($post)) {
            return null;
        }

        if (!$this->validate()) {
            return null;
        }

        $handle = UploadedFile::getInstance($this, 'img');
        if(isset($handle)){
            $this->img = UploadedFile::getInstance($this, 'img');
            if ($this->img) {
                $path = '/public/upload/branner_imgs/';
                $uniqid = uniqid();
                $save_path = '/public/upload/branner_imgs/' . $uniqid  . '.' . $this->img->extension; ;
                $path = $path . $uniqid . '.' . $this->img->extension;
                $path_dir = dirname(Yii::getAlias('@webroot') . $path);
                if (!is_dir($path_dir)) {
                    FileHelper::createDirectory($path, $mode = 0775, $recursive = true);
                }
                $this->img->saveAs(Yii::getAlias('@webroot') . $path);
                $this->img = $save_path;
            }
        }
        $this->save();
        return $this;
    }
}
