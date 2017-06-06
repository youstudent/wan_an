<?php

namespace backend\models;

use common\models\components\Helper;
use Yii;
use backend\models\User;


/**
 * This is the model class for table "{{%record}}".
 *
 * @property string $id
 * @property integer $member_id
 * @property integer $created_at
 * @property string $coin
 * @property integer $updated_at
 * @property integer $status
 */
class Record extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'created_at', 'updated_at', 'status'], 'integer'],
            [['coin'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '会员ID',
            'member.name'=>'会员姓名',
            'member.mobile'=>'电话',
            'member.deposit_bank'=>'开户行',
            'member.bank_account'=>'银行账号',
            'created_at' => '申请时间',
            'coin' => '申请金额',
            'updated_at' => '处理时间',
            'status' => '状态',
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
    
    
    // 财务管理的提现记录与  会员建立一对一的关系
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'member_id']);
    }
    
    
    //记录 通过或拒绝
    public static function pass($id,$ids){
        $Helper = new Helper();
        $model=self::findOne(['id'=>$id]);
        if($ids==2){
          $member=Member::findOne(['id'=>$model->member_id]);
          $a_coin=$member->a_coin;
          $member->a_coin=$a_coin+$model->total;
          if($member->save()){
              // 申请拒绝
              $Helper->pool($model->member_id,1,9,$model->total);
          }
        }
        $model->status=$ids;
        $model->updated_at=time();
        if ($model->save() && $ids==1){
             // 申请通过
            $Helper->pool($model->member_id,1,4,$model->coin,$model->charge);
        }
    }
    
    /*//记录  拒绝
    public static function no($id){
        $model=self::findOne(['id'=>$id]);
        $model->status=2;
        $model->updated_at=time();
        $model->save();
    }*/
}


