<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%share_log}}".
 *
 * @property integer $id
 * @property integer $referrer_id
 * @property integer $member_id
 * @property integer $created_at
 */
class ShareLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%share_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'referrer_id', 'member_id'], 'required'],
            [['id', 'referrer_id', 'member_id', 'created_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'referrer_id' => '分享人member_id',
            'member_id' => '生成会员id',
            'created_at' => '生成时间',
        ];
    }
}
