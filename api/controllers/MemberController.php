<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/25
 * Time: 16:10
 */

namespace api\controllers;

use yii;
use api\models\Member;
use api\models\Bonus;
use api\models\SignupForm;

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
        if($member = $model->getOneMember($member_id)){
            return $this->jsonReturn(1, 'success', $member);
        }
        return $this->jsonReturn(0, 'error');
    }

    /**
     * 获取会员个人资金流水
     * @return array
     */
    public function actionBonus()
    {
        $model = new Bonus();
        $member_id = \Yii::$app->request->get('id');
        if($bonus = $model->getBonus($member_id)){
            return $this->jsonReturn(1, 'success', $bonus);
        }
        return $this->jsonReturn(0, 'error');
    }

    /**
     * 获取提交的登录表单
     * @return array
     */
    public function actionLogin()
    {
        $loginModel = new Member();
        if ($loginModel->login(Yii::$app->request->post('id'), Yii::$app->request->post('password'))) {
            return $this->jsonReturn(1, 'success');
        }
        return $this->jsonReturn(0, $loginModel->getErrors('message'));
    }

    public function actionLogout()
    {
        $model = new Member();
        if ($model->logout()) {
            return $this->jsonReturn(1, 'success');
        }
        return $this->goHome();
    }
    /**
     * 获取提交的修改内容update api
     * @return array
     */

    public function actionUpdate()
    {

        $session = Yii::$app->session->get('member');
        $model = Member::findOne($session['member_id']);
        $modelA = new Member();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($member =$modelA->updateDetail($session['member_id'])) {
                return $this->jsonReturn(1, 'success');
            }
        }
        //如果返回false 返回错误信息
        return $this->jsonReturn(0, $model->getErrors('message')[0]);
    }

    /**
     * 获取提交密码修改 api
     * @return array
     */

    public function actionPass()
    {
        $model = new Member();
        if ($model->updatePass(Yii::$app->request->post())) {
            return $this->jsonReturn(1, 'success');
        }
        //如果返回false 返回错误信息
        return $this->jsonReturn(0, $model->getErrors('message')[0]);
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
}