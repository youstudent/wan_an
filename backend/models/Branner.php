<?php

namespace backend\models;

use Yii;
use app\models\User;


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
    public static $status_options=[1=>'启用',0=>'禁用'];
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
            [['name','content','status'],'required' ],
            [['img'], 'file', 'extensions' => 'jpg, png', 'mimeTypes' => 'image/jpeg, image/png',],
            [['name'], 'string', 'max' => 30],
            [['img',],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'img'=>'图片',
            'name' => '名称',
            'status'=>'状态',
            'content' => '文本',
        ];
    }

}
