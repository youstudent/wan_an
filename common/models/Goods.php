<?php

namespace common\models;

use api\models\Order;
use Yii;

/**
 * This is the model class for table "{{%goods}}".
 *
 * @property string $id
 * @property string $name
 * @property string $price
 * @property string $describe
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price'], 'number'],
            [['describe'], 'string'],
            [['name'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'price' => 'Price',
            'describe' => 'Describe',
        ];
    }

    /**
     * 返回带购买信息的商品数据
     * @param $member_id
     * @return array
     */
    public function getGoodsListWithOrder($member_id)
    {
        //获取用户已经购买的果树信息
        $model = new Order();
        //已经购买的商品id
        $goods_id = null;
        if($order = $model->getMemberOrder($member_id)){
            $goods_id = $order['goods_id'];
        }
        $goods_list = $this->getGoodsList();

        if($goods_id){
            foreach($goods_list as $key=> &$val){
                if(intval($val['id']) === $goods_id){
                    $val['has_buy'] = 1;
                }else{
                    $val['has_buy'] = -1;
                }
            }
        }
        return $goods_list;
    }

    /**
     * 返回带图片的商品数据
     * @return array
     */
    public function getGoodsList()
    {
        $query = (new \yii\db\Query());
        $goods = $query->from(Goods::tableName())->all();
        foreach($goods as $k => &$v){
            $v['has_buy'] = 0;
            $v['img'] = $this->getGoodsImgs($v['id']);
        }
        return $goods;
    }

    /**
     * 获取商品详情
     * @param $id
     * @return array|bool
     */
    public function getGoodDetails($id)
    {
        $query = (new \yii\db\Query());
        $goods = $query->from(Goods::tableName())->where(['id' => $id])->one();
        if(isset($goods['id']) && !empty($goods['id'])){
            $goods['img'] = $this->getGoodsImgs($id);
        }
        return $goods;
    }

    /**
     * 获取商品的图片
     * @param $goods_id
     * @return array
     */
    protected function getGoodsImgs($goods_id)
    {
        $goods_imgs =  (new \yii\db\Query())->from(GoodsImg::tableName())->where(['goods_id'=> $goods_id])->select('img_path')->one();
        if(isset($goods_imgs['img_path']) && !empty($goods_imgs['img_path'])){
            $goods_imgs['img_path'] = Yii::$app->params['img_domain'] . $goods_imgs['img_path'];
        }
        return $goods_imgs['img_path'] ? $goods_imgs['img_path'] : '';
    }
}
