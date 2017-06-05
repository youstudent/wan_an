<?php

namespace backend\models;

use think\Exception;
use Yii;
use backend\models\Member;
use backend\models\Bonus;
use common\models\components\Helper;


/**
 * This is the model class for table "wa_district".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $district
 * @property integer $seat
 * @property integer $created_at
 */
class District extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%district}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'district'], 'required'],
            [['member_id', 'district', 'seat', 'created_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '会员id',
            'district' => '区域id',
            'seat' => '座位id',
            'created_at' => '添加时间',
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

    //座位调换数据继承
    public function changeSeat($old_id = 0, $new_id = 0) {


        //创建事务
        $tr = Yii::$app->db->beginTransaction();
        try {

            $oldModel = District::find()->where(['member_id' => $old_id])->all();
            $newModel = District::find()->where(['member_id' => $new_id])->all();
            $oldBonus = Bonus::find()->where(['member_id' => $old_id, 'type' => 1])->all();
            $newBonus = Bonus::find()->where(['member_id' => $new_id, 'type' => 1])->all();
            //数据替换继承
//            var_dump($oneModel);die;
            $flag = true;
            foreach ($oldModel as $v) {

                $v->member_id = $new_id;
                if(!$v->save()){
                    throw new \yii\db\Exception('失败'); //手动抛出异常,再由下面捕获。
                }

            }
            foreach ($newModel as $v) {

                $v->member_id = $old_id;
                if(!$v->save()){
                    throw new \yii\db\Exception(); //手动抛出异常,再由下面捕获。
                }

            }
            foreach ($oldBonus as $v) {

                $v->member_id = $new_id;
                if(!$v->save()){
                    throw new \yii\db\Exception(); //手动抛出异常,再由下面捕获。
                }

            }
            foreach ($newBonus as $v) {

                $v->member_id = $old_id;
                if(!$v->save()){
                    throw new \yii\db\Exception(); //手动抛出异常,再由下面捕获。
                }

            }
            $tr->commit();
            return true;

        } catch (Exception $e) {
            //回滚
            $tr->rollBack();
            return false;
        }
    }
}
