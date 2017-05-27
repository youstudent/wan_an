<?php
/**
 * User: Administrator
 * Date: 2017/5/26 - 15:13
 * 商品和订单接口控制器
 */

namespace api\controllers;


use common\models\Goods;
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
        $data = $model->getGoodsList();
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
}