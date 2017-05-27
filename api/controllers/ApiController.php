<?php

namespace api\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class ApiController extends Controller
{
    public $layout = false;
    public $enableCsrfValidation = false;


    public function jsonReturn($code, $message, $data = [], $time = '')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $time = $time ? $time : time();
        return [
            'code' => $code,
            'timestamp' => $time,
            'message' => $message,
            'data' => $data,
            
        ];
    }

}
