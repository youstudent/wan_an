<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/5/25
 * Time: 14:28
 */

namespace api\controllers;

use yii;
use api\models\Record;
use yii\web\Controller;
use yii\web\Response;

class WithdrawalsController extends ApiController
{
    // public $enableCsrfValidation=false; //关闭crf验证
    
    //提现申请接口
    public function actionAdd()
    {
        //\Yii::$app->request->format = Response::FORMAT_JSON;//定义数据为 json数据
        //Yii::$app->response->format = Response::FORMAT_JSON;
        //接收数据
        $model = new Record();
        //调用模型方法传入接收数据
        if ($model->with(\Yii::$app->request->post())) {//如果返回的数据是true说明 申请成功
            
            return $this->jsonReturn(1, 'success');
        }
        //如果返回false 返回错误信息
        return $this->jsonReturn(0, $model->getFirstError('message'));
        
    }
    
    
    //提现申请 列表
    public function actionIndex()
    {
        $model = new Record();
        $data = $model->index(\Yii::$app->request->getQueryParam('id'));
        if ($data) {
            return $this->jsonReturn(1, 'success', $data);
        }
        return $this->jsonReturn(0, $model->getFirstError('message'));
        
    }
    
    //当前会员 金果
    public function actionCoin()
    {
    
        $model = new Record();
        $data = $model->coin();
        if ($data) {
            return $this->jsonReturn(1, 'success', $data);
        }
        return $this->jsonReturn(0, $model->getFirstError('message'));
        
    }
    
    
}