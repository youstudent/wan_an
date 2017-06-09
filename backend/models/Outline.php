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
            'created_at' => '退网时间',
            'updated_at' => '注册时间',
            'member.created_at' => '注册时间'
        ];
    }
    
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'member_id']);
    }
}
