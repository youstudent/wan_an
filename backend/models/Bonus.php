<?php

namespace backend\models;

use Yii;
use backend\models\Member;


/**
 * This is the model class for table "{{%bonus}}".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $type
 * @property integer $coin_amount
 * @property integer $coin_count
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Bonus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bonus}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id'], 'required'],
            [['member_id', 'coin_amount', 'coin_count', 'status', 'created_at', 'updated_at'], 'integer'],
            [['type'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'member.gross_income',
            'member.gorss_bonus',
            'member.a_coin',
            'id' => '序号',
            'member_id' => '会员ID',
            'type' => '奖金类型',
            'coin_amount' => '总收入',
            'coin_count' => '出入流水账金额',
            'status' => '流水状态',
            'created_at' => '获得时间',
            'updated_at' => '更新时间',
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
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'member_id']);
    }

}
