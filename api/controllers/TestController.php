<?php
/**
 * User: harlen-angkemac
 * Date: 2017/5/25 - 下午12:10
 *
 */

namespace api\controllers;


use Yii;

class TestController extends ApiController
{
    public function actionDemo()
    {

        //获取客户端数据如
        //Yii::$app->request->post();
        //Yii::$app->request->queryParams;

        //在模型中处理验证
        //如果有错误。返回 负数的code .加上错误信息
        //return $this->jsonReturn(-200, '钱不够，请充值信仰', []);


        //操作类接口 - 如果成功返回以下信息 如提现。修改个人资料
        //return $this->jsonReturn(1, 'success', []);

        //展示类接口 - 返回对应的展示数据 ,如公告详情
        return $this->jsonReturn(1, 'success', ['title' => '大新闻', 'detail' => '公告为荣']);
    }

}