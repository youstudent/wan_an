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
        header("Access-Control-Allow-Origin: *");
        Yii::$app->response->format = Response::FORMAT_JSON;
        $time = $time ? $time : time();
        return [
            'timestamp' => $time,
            'code' => $code,
            'data' => $data,
            'message' => $message
        ];
    }

}
