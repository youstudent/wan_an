<?php

namespace backend\models;

use Yii;
use backend\models\Member;
use common\components\Helper;

/**
 * This is the model class for table "wa_deposit".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $type
 * @property integer $operation
 * @property integer $num
 * @property integer $created_at
 * @property integer $updated_at
 */
class Deposit extends \yii\db\ActiveRecord
{
    public $username;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%deposit}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username','type','operation'], 'required'],
            [['member_id', 'type', 'operation', 'num', 'created_at', 'updated_at'], 'integer'],
            [['num'], 'number', 'min'=> 1, 'max'=> '2147483647']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '充值记录自增ID',
            'member_id' => '会员ID',
            'type' => '币种',
            'operation' => '',
            'num' => '充值数量',
            'created_at' => '创建时间 奖金获得时间',
            'updated_at' => '更新时间',
            'username' => '用户名',
        ];
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

    //充值和扣除
    public function deposit($post)
    {
        if(!$this->load($post)){
            return false;
        }
        if(!$this->validate()){
            return false;
        }

        $member = Member::findOne(['username' => $this->username]);
        if (!isset($member) || empty($member)) {
            Yii::$app->session->setFlash('error', '用户不存在');
            return null;
        }
        $this->created_at = time();
        //如果是扣除，先判断数量是否够
        $transaction = Yii::$app->db->beginTransaction();
        if ($this->operation == 2) {
            if($this->type == 1){
                if ($member->a_coin < $this->num) {

                    Yii::$app->session->setFlash('error', '金果不足,扣除失败');
                    return null;
                }
                $member->a_coin =  $member->a_coin - $this->num;
            }
            if($this->type == 2){
                if ($member->b_coin < $this->num) {

                    Yii::$app->session->setFlash('error', '金种子不足,扣除失败');
                    return null;
                }
                $member->b_coin =  $member->b_coin - $this->num;
            }

            $this->member_id = $member->id;

            if(!$this->save(false) || !$member->save(false)){
                $transaction->rollBack();
                return false;
            }
            $transaction->commit();
            return Helper::saveBonusLog($member->id, $this->type, 7, $this->num, 0, ['note'=> '后台扣除']);
        }else{
            if($this->type == 1){
                $member->a_coin = $member->a_coin + $this->num;
            }
            if($this->type == 2){
                $member->b_coin = $member->b_coin + $this->num;
            }

            $this->member_id = $member->id;

            if(!$this->save(false) || !$member->save(false)){
                $transaction->rollBack();
                return false;
            }
            $transaction->commit();
            return Helper::saveBonusLog($member->id, $this->type, 6, $this->num, 0, ['note'=> '后台充值']);
        }
    }

    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id'=> 'member_id'])->alias('member');
    }


}
