<?php
namespace api\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use api\models\PasswordResetRequestForm;
use api\models\ResetPasswordForm;
use api\models\SignupForm;
use api\models\ContactForm;
use api\models\Member;
use yii\captcha\CaptchaAction;

/**
 * Site controller
 */
class SiteController extends ApiController
{

    /**
     * 获取提交的登录表单
     * @return array
     */
    public function actionLogin()
    {
        $loginModel = new Member();
//        if ($loginModel->outTime()) {
//            return $this->jsonReturn(0, '服务器维护中,请稍后再试');
//        }
        if (!$this->createAction('captcha')->validate(Yii::$app->request->post('authCode'), true)) {
            return $this->jsonReturn(0, '验证码错误');
        }

        if ($loginModel->login(Yii::$app->request->post('username'), Yii::$app->request->post('password'))) {
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
     * 验证码
     * @return
     */
    public function actions()
    {
        return [
//                 'captcha' =>
//                    [
//                        'class' => 'yii\captcha\CaptchaAction',
//                        'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
//                    ],  //默认的写法
            'error' => [
                'class' => 'yii\web\CaptchaAction',
            ],
            'captcha' => [
                'class' => 'api\models\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'backColor'=>0x000000,//背景颜色
                'maxLength' => 4, //最大显示个数
                'minLength' => 4,//最少显示个数
                'height'=>25,//高度
                'width' => 50,  //宽度
                'foreColor'=>0xffffff,     //字体颜色
                'offset'=>4,        //设置字符偏移量 有效果
                //'controller'=>'login',        //拥有这个动作的controller
            ],
        ];
    }

}
