<?php

namespace common\models;

use api\models\Member;
use rmrevin\yii\fontawesome\FA;
use Symfony\Component\DomCrawler\Field\InputFormField;
use Yii;

/**
 * This is the model class for table "{{%give}}".
 *
 * @property string $id
 * @property integer $member_id
 * @property integer $give_member_id
 * @property integer $type
 * @property integer $created_at
 * @property string $give_coin
 */
class Give extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%give}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'give_member_id', 'type', 'created_at'], 'integer'],
            [['give_coin'], 'number'],
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
            'give_member_id' => '赠送人的ID',
            'type' => '赠送类型',
            'created_at' => '赠送时间',
            'give_coin' => '赠送金额',
        ];
    }
    
    
    //金果和金种子的赠送
    public function give($data){
        $id = 2;
        $member = Member::findOne(['id' => $id]);
        $result = Member::findOne(['parent_id' => $id]);
        $give_member  = Member::findOne(['id'=>$data['give_member_id']]);
        if ($give_member==false || $give_member==null){
            $this->addError('message', '没有该会员');
            return false;
        }
        if ($result == null || $result == false) {
            $this->addError('message', '必须直推一个人,才能赠送金果和金种子');
            return false;
        }
        
        if (isset($data['a_coin'])){
            if ($data['give_coin'] > $member->a_coin) {
                $this->addError('message', '你的金果余额不足');
                return false;
            }
            $this->type=0;
            $member->a_coin=($member->a_coin-$data['give_coin']);
            $give_member->a_coin=($give_member->a_coin+$data['give_coin']);
        }else if (isset($data['b_coin'])){
            if ($data['give_coin'] > $member->b_coin) {
                $this->addError('message', '你的金种子余额不足');
                return false;
            }
            $this->type=1;
            $member->b_coin=($member->b_coin-$data['give_coin']);
            $give_member->b_coin=($give_member->b_coin+$data['give_coin']);
        }
        
        if ($member->save(false)){
            if ($give_member->save(false)){
                $this->member_id=$id;
                $this->give_member_id=$data['give_member_id'];
                $this->created_at=time();
                $this->give_coin=$data['give_coin'];
                if ($this->save(false)){
                    return true;
                }
            }
            
        }
        return false;
        
    }
    
    
    //赠送记录
    public function gives(){
        $member_id=2;
        $data = Give::find()->where(['member_id'=>$member_id])->all();
        if ($data==false || $data==null){
            $this->addError('message','没有赠送数据');
            return false;
        }
        return $data;
    }
    
    
    //获赠记录
    public function gain(){
        $give_member_id = 3;
        $data = Give::find()->where(['give_member_id'=>$give_member_id])->all();
        if ($data==false || $data==null){
            $this->addError('message','没有获赠送数据');
            return false;
        }
        return $data;
    }
}
