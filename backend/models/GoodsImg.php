<?php

namespace backend\models;

use Yii;


/**
 * This is the model class for table "{{%goods_img}}".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property string $img_path
 */
class GoodsImg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_img}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['img_path'], 'required', 'message' => "请选择图片上传", 'on'=> 'add'],
            [['goods_id'], 'integer'],
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
            'goods_id' => '模型名',
            'img_path' => '图片',
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

    public static function bindGoods($goods_id, $ids, $is_reaplace = false)
    {
        $ids = trim($ids, ',');
        if(strpos($ids, ',') === false){
            $ids  =(int)$ids;
            //var_dump($ids);exit;
          // $ids[] = $ids;
        }else{
            $ids = explode(',', $ids);
        }
        if($is_reaplace){
            //先将以前的取消掉
            Yii::$app->db->createCommand()->update(self::tableName(), ['goods_id'=>0], ['goods_id'=>$goods_id])->execute();
        }
        $model = GoodsImg::findOne(['id'=>$ids]);
        $model->goods_id = $goods_id;
        $model->save();

        return true;
    }
}
