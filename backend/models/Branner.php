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
            [['name','content','img'],'required' ],
            [['img'], 'file', 'extensions' => 'jpg, png', 'mimeTypes' => 'image/jpeg, image/png',],
            [['name'], 'string', 'max' => 30],
            [['status'],'safe']
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
            'content' => '文本',
        ];
    }

}
