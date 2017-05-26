<?php

namespace api\models;

use app\models\Bonus;
use backend\models\Member;
use Symfony\Component\CssSelector\Node\ElementNode;
use Symfony\Component\DomCrawler\Tests\Field\InputFormFieldTest;
use Yii;


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
            [['coin'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coin' => '申请金额',
        ];
    }
    
    
    //处理申请提现
    public function with($data){
      if ($data['coin']<=0){
           $message= '提现金额不能为负数或0';
           $code = 0;
           $this->addError('code',$code);
           $this->addError('message',$message);
           return false;
       };
       if ($data['coin']%100 !=0){
           $message = '提现金额必须是100的倍数';
           $code = 0;
           $this->addError('code',$code);
           $this->addError('message',$message);
           return false;
       };
       
       $id = 2;//模拟数据
       
       $result =Member::findOne(['parent_id'=>$id]);
       if ($result == null){
           $message = '必须直推至少一人才能提现';
           $code = 0;
           $this->addError('code',$code);
           $this->addError('message',$message);
           return false;
       };
       $result=Member::findOne(['id'=>$id]);
       if ($result->a_coin < $data['coin']){
           $message = '你的余额不足';
           $code = 0;
           $this->addError('code',$code);
           $this->addError('message',$message);
           return false;
       }
     
       if ($this->getErrors('code')!==0){
           //申请提现成功 要扣掉 10%的手续费
           $this->coin=$data['coin']-($data['coin']* 0.1);
           $this->member_id=$id;
           $this->created_at=time();
           $this->status=0;
           if ($this->save()){
                   // 申请成功减去会员对应的金果
                   $result->a_coin=$result->a_coin-$data['coin'];
                   $result->save();
                   //保存数据到 流水表里面
                   $Bonus=new Bonus();
                   $Bonus->member_id=$id;
                   $Bonus->type=4;
                   $Bonus->status=1;
                   $Bonus->coin_count=$data['coin']-($data['coin']* 0.1);
                   $Bonus->updated_at=time();
                   $Bonus->save(false);
               }
           }
        
      return true;
      
    }
 
    
}


