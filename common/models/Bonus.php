<?php

namespace common\models;

use Yii;

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
        ];
    }
}
