<?php

namespace backend\models;

use Yii;
use backend\models\Member;


/**
 * This is the model class for table "{{%outline}}".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Outline extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%outline}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'status', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '记录ID',
            'member_id' => '退网会员ID',
            'member.name' => '会员姓名',
            'member.mobile' => '电话',
            'status' => '账号状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->createDate = date('Y-m-d H:i:s');
            $this->memberCreate = Yii::$app->member->id;
            $this->memberUpdate = Yii::$app->member->id;
        } else {
            $this->updateDate = date('Y-m-d H:i:s');
            $this->memberUpdate = Yii::$app->member->id;
        }
        return parent::beforeSave($insert);
    }
    
    public function getMemberCreateLabel() {

        $user = Member::find()->select('name')->where(['id' => $this->userCreate])->one();
        return $user->name;
    }

    public function getMemberUpdateLabel() {
        $user = Member::find()->select('name')->where(['id' => $this->userUpdate])->one();
        return $user->name;
    }

    */
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'member_id']);
    }
}
