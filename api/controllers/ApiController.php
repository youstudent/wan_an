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

        $headers = Yii::$app->response->headers;
        $headers->add("Access-Control-Allow-Origin", '*');
        $headers->add("Access-Control-Allow-Headers", 'x-requested-with,content-type');
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
