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

use Codeception\Module\REST;
use common\models\Give;
use api\models\Bonus;
use api\models\SignupForm;

use api\models\Fruiter;

class MemberController extends ApiController
{
    /**
     * 获取会员个人信息和团队信息
     * @return array
     */
    public function actionMemberdetail()
    {
        
        $model = new Member();
        if ($member = $model->getOneMember()) {
            return $this->jsonReturn(1, 'success', $member);
        }
        return $this->jsonReturn(0, 'error');
    }
    
    /**
     * 获取会员个人收益明细
     * @return array
     */
    public function actionBonus()
    {
        $model = new Bonus();
        $type = \Yii::$app->request->get('type');
        if ($bonus = $model->getBonus($type)) {
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
        return $this->jsonReturn(0, $loginModel->getFirstError('message'));
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
        return $this->jsonReturn(0, $model->getFirstError('message'));
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
        return $this->jsonReturn(0, $model->getFirstError('message'));
    }
    
    //会员的金果和金种子
    public function actionCoin()
    {
        $id = 2;
        $data = Member::find()->where(['id' => $id])->select(['a_coin', 'b_coin'])->all();
        if ($data) {//如果返回的数据是true说明 申请成功
            return $this->jsonReturn(1, 'success', $data);
        }
        //如果返回false 返回错误信息
        return $this->jsonReturn(0, '未查询到会员信息');
        
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
        return $this->jsonReturn(0, $model->getFirstError('message'));
    }
    
    //赠送记录
    public function actionGives()
    {
        $model = new Give();
        $data = $model->gives();
        if ($data) {
            return $this->jsonReturn(1, 'success', $data);
        }
        return $this->jsonReturn(0, $model->getFirstError('message'));
        
    }
    
    //获赠记录
    public function actionGain()
    {
        $mode = new Give();
        $data = $mode->gain();
        if ($data) {
            return $this->jsonReturn(1, 'success', $data);
        }
        return $this->jsonReturn(0, $mode->getFirstError('message'));
    }
    
    //我的果树
    public function actionFruiter()
    {
        $model = new Fruiter();
        if ($fruiter = $model->getFruiter()) {
            return $this->jsonReturn(1, 'success', $fruiter);
        }
        return $this->jsonReturn(0, '你还没有认购果树');
    }
    
    /**
     * 注册会员
     * @return array
     */
    public function actionRegister()
    {
        $model = new RegisterForm();
        $member_id = 1;
        
        if ($model->register(Yii::$app->request->post(), $member_id)) {
            return $this->jsonReturn(1, 'success', ['vip_number' => $model->vip_number]);
        }
        return $this->jsonReturn(0, $model->errorMsg);
    }
    
    /**
     * 返回会员信息
     * @return array
     */
    public function actionValidate()
    {
        $model = new Member();
        if ($one = $model->getOne(Yii::$app->request->queryParams)) {
            return $this->jsonReturn(1, 'success', $one);
        }
        
        return $this->jsonReturn(0, $model->getFirstError('message'));
    }
    
}