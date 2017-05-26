<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%member}}".
 *
 * @property integer $id
 * @property integer $site
 * @property integer $parent_id
 * @property string $name
 * @property string $password
 * @property string $mobile
 * @property string $deposit_bank
 * @property string $bank_account
 * @property string $address
 * @property integer $group_num
 * @property integer $child_num
 * @property integer $a_coin
 * @property integer $b_coin
 * @property integer $gross_income
 * @property integer $gorss_bonus
 * @property integer $last_login_time
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Member extends \yii\db\ActiveRecord
{
    public $child;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['site', 'parent_id', 'group_num', 'child_num', 'a_coin', 'b_coin', 'gross_income', 'gorss_bonus', 'last_login_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'password', 'mobile', 'deposit_bank', 'bank_account', 'address'], 'string', 'max' => 255],
            ['status', 'in', 'range' => [0,1]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '会员自增ID',
            'site' => '座位号',
            'parent_id' => '直推会员id',
            'name' => '用户姓名',
            'password' => '会员密码',
            'mobile' => '电话',
            'deposit_bank' => '开户行',
            'bank_account' => '银行账号',
            'address' => '地址',
            'group_num' => '区数量',
            'child_num' => '直系挂靠的会员数',
            'a_coin' => '金果数',
            'b_coin' => '金种子数',
            'gross_income' => '总收入',
            'gorss_bonus' => '总提成',
            'last_login_time' => '最后登录时间',
            'status' => '状态 0:被冻结 1:正常 2:已退网',
            'created_at' => '创建时间 注册时间 入网时间',
            'updated_at' => '更新时间 退网时间',
        ];
    }

    /**
     * 获取一个会员资料
     * 算法思路：
     *  1.查询会员是否存在
     *  2.查询会员的相关资料
     *  3.查询会员的附属资料
     * @param string $member_id
     * @return array
     */
    public function getOneMember($member_id = '')
    {
        if (!$this->validate()) {
           return null;
        }
        $data = Member::findOne($member_id)->toArray();
        if(!isset($data) || empty($data)){
            return null;
        }
        $data['child'] = 100;
        return $data;
    }
}
