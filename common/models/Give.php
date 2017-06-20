<?php

namespace common\models;

use api\models\Member;
use common\models\components\Helper;
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
        $session = Yii::$app->session->get('member');
        $member_id = $session['member_id'];

        $member = Member::findOne(['id' => $member_id]);
        $result = Member::findOne(['parent_id' => $member_id]);
        $give_member  = Member::findOne(['vip_number'=>$data['give_member_id']]);
        if ($data['give_coin']<=0){
            $this->addError('message','赠送金果和金种子必须大于0');
            return false;
        }
        if ($member_id==$data['give_member_id']){
            $this->addError('message','不能转金果和金种子给自己');
            return false;
        }
        if ($give_member==false || $give_member==null){
            $this->addError('message', '没有该会员');
            return false;
        }
        if ($result == null || $result == false) {
            $this->addError('message', '必须直推一个人,才能赠送金果和金种子');
            return false;
        }
        if ($data['coinType']==1){
            if ($data['give_coin'] > $member->a_coin) {
                $this->addError('message', '你的金果余额不足');
                return false;
            }
            $this->type=1;
            $member->a_coin=($member->a_coin-$data['give_coin']);
            $give_member->a_coin=($give_member->a_coin+$data['give_coin']);
        }else if ($data['coinType']==2){
            if ($data['give_coin'] > $member->b_coin) {
                $this->addError('message', '你的金种子余额不足');
                return false;
            }
            $this->type=2;
            $member->b_coin=($member->b_coin-$data['give_coin']);
            $give_member->b_coin=($give_member->b_coin+$data['give_coin']);
        }
        
        if ($member->save(false)){
            if ($give_member->save(false)){
                $memberModel = Member::findOne($data['give_member_id']);
                $this->member_id=$member->vip_number;
                $this->give_member_id=$data['give_member_id'];
                $this->created_at=time();
                $this->give_coin=$data['give_coin'];
                if ($this->save(false)){
                    $ext_data=[];
                    $ext_data['member_id']=$member->vip_number;;
                    $ext_data['give_member_id']=$data['give_member_id'];
                    $ext_data['give_coin']=$data['give_coin'];
                    $ext_data['coin_type']=$data['coinType'];
                    $ext_data['type']=8;
                    $new_ext_data = serialize($ext_data);
                    $Helper= new Helper();
                    if ($Helper->pool($member_id,$data['coinType'],8,$data['give_coin'],null,$new_ext_data)===false
                        || $Helper->pool($memberModel->id,$data['coinType'],11,$data['give_coin'],null,$new_ext_data)===false){
                        return false;
                    }

                    return true;
                }
            }
            
        }
        return false;
        
    }
    
    
    //赠送记录
    public function gives(){
        $session = Yii::$app->session->get('member');
        $member_id = $session['vip_number'];

        $data = Give::find()->where(['member_id'=>$member_id])->orderBy(['created_at' => SORT_DESC])->all();
        if ($data==false || $data==null){
            $this->addError('message','没有赠送数据');
            return false;
        }
        foreach ($data as &$v){
            $v['created_at']=date('Y/m/d H:i:s',$v['created_at']);
        }
        return $data;
    }
    
    
    //获赠记录
    public function gain(){
        $session = Yii::$app->session->get('member');
        $member_id = $session['vip_number'];

        $data = Give::find()->where(['give_member_id'=>$member_id])->orderBy(['created_at' => SORT_DESC])->all();
        if ($data==false || $data==null){
            $this->addError('message','没有获赠送数据');
            return false;
        }
        foreach ($data as &$v){
            $v['created_at']=date('Y/m/d H:i:s',$v['created_at']);
        }
        return $data;
    }

    public function getGain()
    {
        return $this->hasOne(Member::className(), ['id'=>'give_member_id'])->alias('gain');
    }
    public function getGive()
    {
        return $this->hasOne(Member::className(), ['id'=>'member_id'])->alias('give');
    }
}
