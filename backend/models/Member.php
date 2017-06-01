<?php

namespace backend\models;

use Yii;
use backend\models\Bonus;

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
 * @property integer $gross_bonus
 * @property integer $last_login_time
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Member extends \yii\db\ActiveRecord
{
    public $msg;
    public $state;

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
            [['site', 'parent_id', 'group_num', 'child_num', 'a_coin', 'b_coin', 'gross_income', 'gross_bonus', 'last_login_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'password', 'mobile', 'deposit_bank', 'bank_account', 'address'], 'string', 'max' => 255],
            ['state', 'in', 'range' => [0,1,2]]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '会员ID',
            'site' => '座位号',
            'bonus.coin_amount' => '总收入',
            'bonus.coin_count' => '金额',
            'bonus.id' => '序号',
            'bonus.type' => '奖金类型',
            'bonus.create_at' => '获得时间',
            'parent_id' => '直推会员ID',
            'name' => '用户姓名',
            'password' => '会员密码',
            'mobile' => '电话',
            'deposit_bank' => '开户行',
            'bank_account' => '银行账号',
            'address' => '地址',
            'group_num' => '区数量',
            'child_num' => '直系挂靠会员数',
            'a_coin' => '金果',
            'b_coin' => '金种子',
            'gross_income' => '总收入',
            'gross_bonus' => '总提现',
            'last_login_time' => '最后登录时间',
            'status' => '状态',
            'created_at' => '注册时间',
            'updated_at' => '更新时间',
        ];
    }


    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
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

    /**
     * @param $param id status
     * @return bool|null
     */
    public function changeMember($param){
        $this->msg = $param['state'] == 1 ? '冻结' : '解冻';
        if(!$this->validate()){
            return null;
        }

        $info = Member::findOne(['id'=>$param['id']]);
        $info->status = $param['state'];

        return $info->save();
    }
    public function getBonus()
    {
        return $this->hasOne(Bonus::className(), ['member_id' => 'id']);
    }
}
