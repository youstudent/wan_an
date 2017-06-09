<?php

namespace api\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Response;

class ApiController extends Controller
{
    public $layout = false;
    public $enableCsrfValidation = false;


    public function jsonReturn($code, $message, $data = [], $time = '',$title='')
    {

//        $headers = Yii::$app->response->headers;
//        $headers->add("Access-Control-Allow-Origin", '*');
//        $headers->add("Access-Control-Allow-Headers", 'x-requested-with,content-type');
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
