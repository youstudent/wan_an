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
            [['title','img','content'], 'required'],
            [['content'], 'string'],
            [['created_at'], 'integer'],
            [['title'], 'string', 'max' => 30]
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
            'created_at' => '发布时间',
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
