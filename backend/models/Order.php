<?php

namespace backend\models;

use Yii;
use api\models\User;


/**
 * This is the model class for table "{{%order}}".
 *
 * @property integer $id
 * @property string $order_sn
 * @property integer $member_id
 * @property string $name
 * @property string $price
 * @property integer $status
 * @property integer $goods_id
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'status', 'goods_id'], 'integer'],
            [['price'], 'number'],
            [['goods_id'], 'required'],
            [['order_sn'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'        => 'ID',
            'order_sn'  => '订单号',
            'member_id' => '购买会员id',
            'name'      => '商品名字',
            'price'     => '商品价格',
            'status'    => '状态',
            'goods_id'  => '商品id',
        ];
    }
    
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['member_id' => 'id']);
    }
}
