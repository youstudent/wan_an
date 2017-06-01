<?php
/**
 * User: Administrator
 * Date: 2017/5/26 - 15:13
 * 商品和订单接口控制器
 */

namespace api\controllers;


use common\models\Goods;
use api\models\Order;
use Yii;

class ShopController extends ApiController
{

    /**
     * 获取商品列表
     * @return array
     */
    public function actionGoodsList()
    {
        $model = new Goods();
        $member_id = 1;
        $data = $model->getGoodsListWithOrder($member_id);
        if(!isset($data) || count($data) <= 0 ){
            return $this->jsonReturn( 0, 'error');
        }
        return $this->jsonReturn( 1, 'success', $data);
    }

    /**
     * 获取商品详情
     * @return array
     */
    public function actionGoodsDetails()
    {
        $model = new Goods();
        $data = $model->getGoodDetails(Yii::$app->request->getQueryParam('goods_id'));
        if(!isset($data) || count($data) <= 0 ){
            return $this->jsonReturn( 0, 'error');
        }
        return $this->jsonReturn( 1, 'success', $data);
    }

    /**
     * 购买商品
     * @return array
     */
    public function actionBuy()
    {
        $member_id = 1;
        $model = new Order();
        if(!$model->buy($member_id, Yii::$app->request->getQueryParam('goods_id'))){
            return $this->jsonReturn( 0, $model->errorMsg);
        }
        return $this->jsonReturn( 1, 'success');
    }
}