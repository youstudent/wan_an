<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/25
 * Time: 16:10
 */

namespace api\controllers;

use api\models\RegisterForm;
use Codeception\Lib\Di;
use common\models\District;
use yii;
use api\models\Member;

use Codeception\Module\REST;
use common\models\Give;
use api\models\Bonus;
use api\models\SignupForm;

use api\models\Fruiter;
use yii\helpers\ArrayHelper;

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
        return $this->jsonReturn(0, '查询超时');
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
        return $this->jsonReturn(1, '用户暂无数据');
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
        $session = Yii::$app->session->get('member');
        $member_id = $session['member_id'];
        $data = Member::find()->where(['id' => $member_id])->select(['a_coin', 'b_coin'])->all();
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

        if ($model->register(Yii::$app->request->post(), ArrayHelper::getValue(Yii::$app->session->get('member'), 'vip_number'))) {
            return $this->jsonReturn(1, 'success', ['vip_number' => $model->vip_number]);
        }
        return $this->jsonReturn(0, $model->errorMsg ? $model->errorMsg : '服务器繁忙');
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

    /**
     * 获取树状信息
     * @return array
     */
    public function actionTree()
    {

        $model = new District();
        if($data = $model->simpleTree(Yii::$app->request->post(), ArrayHelper::getValue(Yii::$app->session->get('member'), 'vip_number'))){
            return $this->jsonReturn(1, 'success', $data);
        }
        return $this->jsonReturn(0, $model->errorMsg);
    }

    /**
     * 退网 api
     * @return array
     */
    public function actionOutline()
    {
        $model = new Member();
        if ($model->outLine()) {
            return $this->jsonReturn(1, 'success');
        }
        //如果返回false 返回错误信息
        return $this->jsonReturn(0, $model->getFirstError('message'));
    }
    /**
     * 后台系谱图
     * @return \yii\web\Response
     */
    public function actionFullTree()
    {
        $model = new District();
        $json = [ 'code'=> 0, 'data' => [], 'message'=> 'error'];
        if($data = $model->getFullTree(Yii::$app->request->post()))
        {
            $json = [ 'code'=> 1, 'data' => $data, 'message'=> 'success'];
            return $this->asJson($json);
        }
        $json = [ 'code'=> 0, 'data' => [], 'message'=> $model->getFirstErrors()];
        return $this->asJson($json);
    }
    /**
     * 账户关联
     */
    public function actionRelate()
    {
        $model = new Member();
        if ($data = $model->relate()) {
            return $this->jsonReturn(1, 'success', $data);
        }
        //如果返回null 返回错误信息
        return $this->jsonReturn(0, '无关联用户');
    }
}