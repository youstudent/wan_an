<?php

namespace api\models;

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
            'name' => '标题',
            'img' => '图片',
            'content' => '文本',
        ];
    }
     //广告管理列表
    public function branner(){
           $model = self::find()->select(['id','img','http'])->orderBy('id DESC')->limit(3)->all();
        if ($model === false){
            return false;
        }
            return $model;
        
    }
    
    //广告详情
    public function listid($id){
        $model = self::findOne(['id'=>$id]);
        if ($model === false){
            return false;
        }
            return $model;
    }
    
}
