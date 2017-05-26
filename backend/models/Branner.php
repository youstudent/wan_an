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
    public $img;
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
            [['name','content_img','img'],'required' ],
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
            'name' => '轮播图名称',
            'img' => '图片',
            'content_img' => '轮播图文本',
        ];
    }

    /**
    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->createDate = date('Y-m-d H:i:s');
            $this->userCreate = Yii::$app->user->id;
            $this->userUpdate = Yii::$app->user->id;
        } else {
            $this->updateDate = date('Y-m-d H:i:s');
            $this->userUpdate = Yii::$app->user->id;
        }
        return parent::beforeSave($insert);
    }
    
    public function getUserCreateLabel() {

        $user = User::find()->select('username')->where(['id' => $this->userCreate])->one();
        return $user->username;
    }

    public function getUserUpdateLabel() {
        $user = User::find()->select('username')->where(['id' => $this->userUpdate])->one();
        return $user->username;
    }

    */
    }
