<?php

namespace api\models;

use api\models\Bonus;
use backend\models\Member;
use Codeception\Module\REST;
use common\models\components\Helper;
use rmrevin\yii\fontawesome\FA;
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
    public function with($data)
    {
       // $id = $data['id'];//模拟数据
        $session = Yii::$app->session->get('member');
        $id = $session['member_id'];

        $re = Record::findOne(['member_id' => $id, 'date' => date('Y-m-d')]);
        if ($re !== null) {
            $message = '每天只能提现一次';
            $code = 0;
            $this->addError('code', $code);
            $this->addError('message', $message);
            return false;   
        }
        if ($data['coin'] <= 0) {
            $this->addError('code', 0);
            $this->addError('message','提现金额不能为负数或0');
            return false;
        };
        if ($data['coin'] % 100 != 0) {
            $this->addError('code', 0);
            $this->addError('message', '提现金额必须是100的倍数');
            return false;
        };
        $member = Member::findOne(['id' => $id]);
        if ($member->child_num == 0) {
            $this->addError('code', 0);
            $this->addError('message', '必须直推至少一人才能提现');
            return false;
        };
        if ($member->a_coin < abs($data['coin']) ) {
            $this->addError('code', 0);
            $this->addError('message', '你的余额不足');
            return false;
        }
        
        if ($this->getErrors('code') !== 0) {
            //申请提现成功 要扣掉 10%的手续费
            $charge = ($data['coin'] * 0.1);
            $this->coin = $data['coin'] - $charge;
            $this->charge = $charge;
            $this->total = $data['coin'];
            $this->member_id = $id;
            $this->date = date('Y-m-d');
            $this->created_at = time();
            $this->status = 0;
            if ($this->save(false)) {
                // 申请成功减去会员对应的金果
                $member->a_coin = $member->a_coin - abs($data['coin']);
                if ($member->save(false)){
                        return true;
                }
                
            }
        }
        
        return true;
    }
    
    //提现列表
    public function index()
    {
        $session = Yii::$app->session->get('member');
        $member_id = $session['member_id'];
        //根据会员id 查询用户 申请记录数据
        $model = self::find()->select(['id', 'member_id', 'created_at', 'total', 'status'])
                ->where(['member_id' => $member_id])
                ->orderBy(['created_at' => SORT_DESC])
                ->all();
        if ($model === false || $model ===null) {
            $this->addError('code', 0);
            $this->addError('message', '没有提现记录');
            return false;
        }
        foreach ($model as &$v) {
            $v['created_at'] = date('Y/m/d H:i:s', $v['created_at']);
        }
        return $model;
        
    }
    
    
    //当前会员金果
    public function coin(){
        $session = Yii::$app->session->get('member');
        $member_id = $session['member_id'];

        $data = \api\models\Member::find()->select('a_coin')->where(['id'=>$member_id])->all();
        if ($data == false || $data == null){
            $this->addError('code', 0);
            $this->addError('message', '用户信息不存在');
            return false;
        }
        return $data;
    }
    
    
}


