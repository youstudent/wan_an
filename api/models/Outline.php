<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "wa_outline".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string  $username
 * @property string $name
 * @property integer $vip_number
 * @property string  $mobile
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $ext_data
 */
class Outline extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wa_outline';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['ext_data'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '退网记录自增ID',
            'member_id' => '退网会员ID',
            'status' => '账号状态 1:正常 0:禁用',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'ext_data' => '扩展',
        ];
    }
}
