<?php

namespace api\models;

use Yii;
use yii\base\Model;

/**
 * Class Session
 * @package tea\models
 */
class Session extends Model
{
    /**
     * 用户登录成功、设置session
     * @param array $member 用户基本数据
     */
    public function login($member = [])
    {
        Yii::$app->session->set('id',$member['id']);

    }

    /**
     * 用户退出登录操作的session
     */
    public function logout()
    {
        Yii::$app->session->removeAll();
    }
}