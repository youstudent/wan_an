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
}
