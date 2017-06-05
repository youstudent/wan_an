<?php

namespace backend\models;

use Yii;
use backend\models\Bonus;

/**
 * This is the model class for table "wa_member".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property string $password
 * @property string $mobile
 * @property string $deposit_bank
 * @property string $bank_account
 * @property string $address
 * @property integer $last_login_time
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $vip_number
 * @property integer $a_coin
 * @property integer $b_coin
 * @property integer $child_num
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
            [['parent_id', 'last_login_time', 'status', 'created_at', 'updated_at', 'vip_number', 'a_coin', 'b_coin', 'child_num'], 'integer'],
            [['vip_number', 'a_coin', 'b_coin', 'child_num'], 'required'],
            [['name', 'password', 'mobile', 'deposit_bank', 'bank_account', 'address'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '会员自增ID',
            'parent_id' => '直推会员id',
            'name' => '用户姓名',
            'password' => '会员密码',
            'mobile' => '电话',
            'deposit_bank' => '开户行',
            'bank_account' => '银行账号',
            'address' => '地址',
            'last_login_time' => '最后登录时间',
            'status' => '状态',
            'created_at' => '创建时间 注册时间 入网时间',
            'updated_at' => '更新时间 退网时间',
            'vip_number' => '会员卡号',
            'a_coin' => '金果数',
            'b_coin' => '金种子数',
            'child_num' => '直推数量',
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
    // 资金
    public function getBonus($num, $id)
    {
        if ($num == 1) {
            $query = (new \yii\db\Query());
            $old = $query->from(Bonus::tableName())->where(['member_id' => $id, 'coin_type' => 1, 'type' => [1,2,3,5]])->sum('num');
            $cover = $query->from(Bonus::tableName())->where(['member_id' => $id, 'coin_type' => 1, 'type' => 4])->sum('num');
            return $old-$cover;
        }
        if ($num == 2) {
            $query = (new \yii\db\Query());
            $old = $query->from(Bonus::tableName())->where(['member_id' => $id, 'coin_type' => 2])->sum('num');
            return $old?$old:0;
        }
        if ($num == 3) {
            $query = (new \yii\db\Query());
            $old = $query->from(Bonus::tableName())->where(['member_id' => $id, 'coin_type' => 1, 'type' => [1,2,3,5]])->sum('num');
            return $old;
        }
        if ($num == 4) {
            $query = (new \yii\db\Query());
            $cover = $query->from(Bonus::tableName())->where(['member_id' => $id, 'coin_type' => 1, 'type' => 4])->sum('num');
            return $cover;
        }

    }
}
