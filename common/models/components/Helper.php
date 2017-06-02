<?php

namespace common\models\components;

use phpDocumentor\Reflection\Types\Null_;
use Yii;

/**
 * This is the model class for table "{{%bonus}}".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $coin_type
 * @property integer $type
 * @property integer $num
 * @property integer $created_at
 * @property integer $updated_at
 */
class Helper extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bonus}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id'], 'required'],
            [['member_id', 'coin_type', 'type', 'num', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '奖金记录自增ID',
            'member_id' => '会员ID',
            'coin_type' => '币种 1:金果 2:金种子',
            'type' => '获得类型 1:绩效2:分享3:额外分享4:提现5:注册奖金6:充值7:扣除',
            'num' => '金额',
            'created_at' => '创建时间 获得时间',
            'updated_at' => '更新时间',
        ];
    }
    
    /**
     * @param null $member_id    用户id
     * @param null $coin_type    币种 1:金果   2:金种子
     * @param null $type         获得类型 1:绩效2:分享3:额外分享4:提现5:注册奖金6:充值7:扣除',
     * @param null $num          币种数量
     * @param null $poundage     只有提现才有手续费
     * @return bool              失败返回false  成功返回true
     * @param null $ext_data      扩展
     */
    
    public function pool($member_id=null,$coin_type=null,$type=null,$num=null,$poundage=null,$ext_data=null){
        $this->member_id=$member_id;
        $this->coin_type=$coin_type;
        $this->type=$type;
        $this->poundage=$poundage;
        $this->num=$num;
        $this->ext_data=$ext_data;
        $this->created_at=time();
        if (!$this->save()){
            return false;
        }
         return true;
        
    }
}
