<?php

namespace backend\models;

use Yii;
use backend\models\Member;


/**
 * This is the model class for table "wa_bonus".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $coin_type
 * @property integer $type
 * @property integer $num
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $poundage
 * @property string $ext_data
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
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id'], 'required'],
            [['member_id', 'coin_type', 'type', 'num', 'created_at', 'updated_at', 'poundage'], 'integer'],
            [['ext_data'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '奖金记录自增ID',
            'member_id' => '会员ID',
            'coin_type' => '币种',
            'type' => '获得类型',
            'num' => '金额',
            'created_at' => '获得时间',
            'updated_at' => '更新时间',
            'poundage' => '手续费',
            'ext_data' => '扩展',
            'relate_username' => '关联账号',
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

    public static function getRelateName($id)
    {
        $query = (new \yii\db\Query());
        $bonus = $query->select('ext_data')->from(Bonus::tableName())
            ->where(['id' => $id])
            ->one();
        $relate_username = json_decode($bonus['ext_data'])->relation;

        return $relate_username;
    }
}
