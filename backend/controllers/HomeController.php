<?php
/**
 * User: harlen-angkemac
 * Date: 2017/6/23 - 上午11:23
 *
 */

namespace backend\controllers;


use yii\filters\AccessControl;
use yii\web\Controller;

class HomeController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['index'],
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index'],
                        'roles'   => ['@'],
                    ],
                ],

            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}