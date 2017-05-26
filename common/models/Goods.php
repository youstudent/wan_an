<?php

namespace common\models;

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
     * 返回带图片的商品数据
     * @return array
     */
    public function getGoodsList()
    {
        $query = (new \yii\db\Query());
        $goods = $query->from(Goods::tableName())->all();
        foreach($goods as $k => &$v){
            $v['img'] = [];

            $goods_imgs = $this->getGoodsImgs($v['id']);
            if(isset($goods_imgs) && count($goods_imgs)){
                foreach($goods_imgs as $img){
                    $v['img'][] = $img;
                }
            }
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
            $goods['img'] = [];
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
        $goods_imgs =  (new \yii\db\Query())->from(GoodsImg::tableName())->where(['goods_id'=> $goods_id])->select('img_path')->column();
        if(isset($goods_imgs) && count($goods_imgs) >0 ){
            foreach($goods_imgs as &$img){
                $img = Yii::$app->params['img_domain'] . $img;
            }
        }
        return $goods_imgs;
    }
}
