<?php

namespace backend\models;

use Yii;
use app\models\User;


/**
 * This is the model class for table "{{%announcements}}".
 *
 * @property string $id
 * @property string $title
 * @property string $content
 * @property integer $created_at
 */
class Announcements extends \yii\db\ActiveRecord
{
    public static $status_option=[1=>'启用',0=>'禁用'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%announcements}}';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content','author'], 'required'],
            [['content'], 'string'],
            [['created_at'], 'integer'],
            [['title'], 'string', 'max' => 30],
            [['status'], 'safe']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'content' => '内容',
            'status' => '状态',
            'created_at' => '发布时间',
            'author'=>'发布者'
        ];
    }
    
}