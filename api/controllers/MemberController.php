<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/25
 * Time: 16:10
 */

namespace api\controllers;

use api\models\RegisterForm;
use common\models\District;
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
    /**
     * 登出
     * @return
     */
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

        $model = new Member();
        if ($model->updateDetail(Yii::$app->request->post())) {
            return $this->jsonReturn(1, 'success');
        }
        //如果返回false 返回错误信息
        return $this->jsonReturn(0, $model->getErrors('message'));
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
        return $this->jsonReturn(0, $model->getErrors('message'));
    }
    public function actionRegister()
    {
        $model = new RegisterForm();
        $post = [
            'name' => 'test',
            'password' => '123456',
            'mobile' => '13688464645'
        ];
        $referrer_id = 1;
        $action_member_id = 1;
        if($model->register($post, $referrer_id, $action_member_id)){
            echo 'success';
        }else{
            echo $model->errorMsg;
        }
    }
}