<?php

namespace backend\models;

use Yii;
use app\models\User;


/**
 * This is the model class for table "{{%goods}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $img
 * @property string $price
 * @property string $describe
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price'], 'number'],
            [['describe'], 'string'],
            [['name'], 'string', 'max' => 30],
            [['img'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名字',
            'img' => '商品图片',
            'price' => '商品价格',
            'describe' => '商品描述',
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
