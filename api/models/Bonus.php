<?php

namespace api\models;

use Yii;

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
            [['type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'type' => 'Type',
            'coin_amount' => 'Coin Amount',
            'coin_count' => 'Coin Count',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * 获取一个会员资金流水
     * @param string $member_id
     * @return array
     */
    public function getBonus($member_id = '')
    {
        $query = (new \yii\db\Query());
        $bonus = $query->from(Bonus::tableName())->where(['member_id' => $member_id])->all();
        if(!isset($bonus) || empty($bonus)){
            return null;
        }
        return $bonus;
    }
    
}
