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
    public function getBonus($type)
    {
        // 获取用户id
        $session = Yii::$app->session->get('member');
        $member_id = $session['member_id'];
        // 测试
        $member_id = 2;

        $query = (new \yii\db\Query());

        // 判断调用的type类型
        // 全部
        if ($type == 0) {
            $bonus = $query->select('type,created_at,num')->from(Bonus::tableName())->where(['member_id' => $member_id, 'coin_type' => 1, 'type' =>[1,2,3,5]])->all();
        }

        // 绩效
        if ($type == 1) {
            $bonus = $query->select('type,created_at,num')->from(Bonus::tableName())->where(['member_id' => $member_id, 'coin_type' => 1, 'type' =>1])->all();
        }

        // 分享
        if ($type == 2) {
            $bonus = $query->select('type,created_at,num')->from(Bonus::tableName())->where(['member_id' => $member_id, 'coin_type' => 1, 'type' =>2])->all();
        }

        if(!isset($bonus) || empty($bonus)){
            return null;
        }
        foreach ($bonus as &$v) {
            $v['created_at'] = date('Y/m/d H:i:s', $v['created_at']);
        }
        return $bonus;
    }
    
    //流水表的问题
}
