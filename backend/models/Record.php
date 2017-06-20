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
            [['member_id', 'created_at', 'updated_at', 'status', 'total'], 'integer'],
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
            'member_name'=>'会员姓名',
            'member_mobile'=>'电话',
            'member_deposit_bank'=>'开户行',
            'member_bank_account'=>'银行账号',
            'created_at' => '申请时间',
            'total' => '申请金额',
            'updated_at' => '处理时间',
            'status' => '状态',
            'member_username' => '会员名',
            'coin' => '提现金额',
        ];
    }
    

    
    
    /**
     * @param $id 传过来的id
     * @param $ids 通过还是拒绝
     */
    public static function pass($id,$ids){
        $Helper = new Helper();
        $model = Record::findOne(['id'=>$id]);

        if($ids==2){
          $member=Member::findOne(['id'=>$model->member_id]);
          $a_coin=$member->a_coin;
          $member->a_coin=$a_coin + $model->total;

          if($member->save(false)){
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
     * @param $datas 传过来的id
     * @param $status 拒绝值为2
     * @param $type 保存的类型为 9提现返回
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

    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id'=> 'member_id'])->alias('member');
    }
}


