<?php

namespace api\models;

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

    /**
     * 获取一个会员资金流水
     * @param string $type
     * @return array
     */
    public function getBonus($type)
    {
        // 获取用户id
        $session = Yii::$app->session->get('member');
        $member_id = $session['member_id'];
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
        $gross = $query->from(Bonus::tableName())->where(['member_id' => $member_id, 'type' => [1,2,3,5]])->sum('num');
        $bonus = ['gross'=>$gross,'data'=>$bonus];
        return $bonus;
    }
    
}
