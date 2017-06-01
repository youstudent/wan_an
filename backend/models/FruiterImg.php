<?php

namespace backend\models;

use Yii;


/**
 * This is the model class for table "wa_fruiter_img".
 *
 * @property integer $id
 * @property integer $fruiter_id
 * @property string $img_path
 */
class FruiterImg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wa_fruiter_img';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fruiter_id'], 'integer'],
            [['img_path'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fruiter_id' => '果树ID',
            'img_path' => '存放路径',
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

    public static function bindFruiter($fruiter_id, $ids)
    {
        $ids = trim($ids, ',');
        if(strpos($ids, ',') === false){
            $ids = $ids;
        }else{
            $ids = explode(',', $ids);
        }

        $model = FruiterImg::findOne(['id'=>$ids]);
        $model->fruiter_id = $fruiter_id;
        $model->save();

        return true;

    }
}
