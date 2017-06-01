<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/25
 * Time: 16:10
 */

namespace api\controllers;


use app\models\Member;
use Codeception\Module\REST;
use common\models\Give;

class MemberController extends ApiController
{
    /**
     * 获取会员个人信息和团队信息
     * @return array
     */
    public function actionMemberdetail()
    {
        $model = new Member();
        $member_id = \Yii::$app->request->get('id');
        if ($member = $model->getOneMember($member_id)) {
            return $this->jsonReturn(1, 'success', $member);
        }
        return $this->jsonReturn(0, 'error');
    }
    
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
    
    
    //会员的金果和金种子
    public function actionCoin(){
        $id=3;
        $data = Member::find()->where(['id'=>$id])->select(['a_coin','b_coin'])->all();
        if ($data) {//如果返回的数据是true说明 申请成功
            return $this->jsonReturn(1, 'success',$data);
        }
        //如果返回false 返回错误信息
        return $this->jsonReturn(0,'未查询到会员信息');
        
    }
    
    
    //赠送申请
    public function actionGive()
    {
        $model = new Give();
        //调用模型方法传入接收数据
        $data = $model->give(\Yii::$app->request->post());
        if ($data) {//如果返回的数据是true说明 申请成功
            return $this->jsonReturn(1, 'success');
        }
        //如果返回false 返回错误信息
        return $this->jsonReturn(0, $model->getErrors('message')[0]);
    }
    
    //赠送记录
    public function actionGives()
    {
        $model = new Give();
        $data = $model->gives();
        if ($data) {
            return $this->jsonReturn(1, 'success', $data);
        }
        return $this->jsonReturn(0, $model->getErrors('message')[0]);
        
    }
    
    
    //获赠记录
    public function actionGain()
    {
        $mode = new Give();
        $data = $mode->gain();
        if ($data) {
            return $this->jsonReturn(1, 'success', $data);
        }
        return $this->jsonReturn(0, $mode->getErrors('message')[0]);
    }
}