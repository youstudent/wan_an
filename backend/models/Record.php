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
    
    
    // 财务管理的提现记录与  会员建立一对一的关系
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'member_id']);
    }
    
    
    /**
     * @param $id 传过来的id
     * @param $ids 通过还是拒绝
     */
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
    
    
    /**
     * @param $datas传过来的id
     * @param $status拒绝值为2
     * @param $type保存的类型为 9提现返回
     */
    public static function batch($datas,$status,$type){
        foreach ($datas as $data){
          $result = Record::findOne(['id'=>$data]);
          $result->status=$status;
          if ($result->save()){
              //修改成功  保存数据到流水表
            $Helper = new Helper();
            $Helper->pool($result->member_id,1,$type,$result->coin,$result->charge);
          }
        }
    }
    
}


