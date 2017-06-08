<?php
namespace api\models;
use common\models\Goods;
use api\models\Member;
use api\models\Fruiter;
use Yii;
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
    public $errorMsg;
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
            [['member_id', 'status','goods_id'], 'integer'],
            [['price'], 'number'],
            [['order_sn'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 30]
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_sn' => '订单号',
            'member_id' => '购买会员id',
            'name' => '商品名字',
            'price' => '商品价格',
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
    public function buy($member_id, $goods_id)
    {
        if($this->getMemberOrder($member_id)){
            $this->errorMsg = '你已购买果树';
            return null;
        }
        //获取果树信息
        $goods = Goods::findOne(['id'=>$goods_id]);
        if(!isset($goods) || empty($goods)){
            $this->errorMsg = '该果树已下架';
            return null;
        }
        //TODO::扣除用户金什么东西
        $this->member_id = $member_id;
        $this->order_sn = 'WA'. date('YmdHis') . $member_id;
        $this->name = $goods['name'];
        $this->price = $goods['price'];
        $this->status = 1;
        $this->goods_id = $goods_id;

        $query = (new \yii\db\Query());
        $name = $query->select('name')->from(Member::tableName())->where(['vip_number' => $member_id])->one();

        $fruiter = new Fruiter();
        $fruiter->member_id = $member_id;
        $fruiter->order_sn = 'WA'. date('YmdHis') . $member_id;
        $fruiter->fruiter_name = $goods['name'];
        $fruiter->status = 0;
        $fruiter->created_at = time();
        $fruiter->save(false);
        return $this->save() ? $this : null;
    }
    /**
     * 获取会员的订单信息
     * @param $member_id
     * @return static
     */
    public function getMemberOrder($member_id)
    {
        return Order::findOne(['member_id'=> $member_id]);
    }
}