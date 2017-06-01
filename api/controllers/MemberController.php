<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/25
 * Time: 16:10
 */

namespace api\controllers;

<<<<<<< HEAD

use api\models\Member;
use Codeception\Module\REST;
use common\models\Give;
=======
use yii;
use api\models\Member;
use api\models\Bonus;
use api\models\SignupForm;
>>>>>>> a1c3c24240f26da19b3822881422747297c8ead9

use yii;
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
        $member_id = \Yii::$app->request->get('id');
        if ($member = $model->getOneMember($member_id)) {
            return $this->jsonReturn(1, 'success', $member);
        }
        return $this->jsonReturn(0, 'error');
    }
<<<<<<< .mine
<<<<<<< HEAD
=======

>>>>>>> .theirs
    
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
=======

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

    //我的果树
    public function actionFruiter()
    {
        $model = new Fruiter();
        $member_id = \Yii::$app->request->get('id');
        if($fruiter = $model->getFruiter($member_id)){
            return $this->jsonReturn(1, 'success', $fruiter);
        }
        return $this->jsonReturn(0, 'error');
    }
}