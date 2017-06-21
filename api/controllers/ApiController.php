<?php

namespace api\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Response;
use api\models\Offline;

class ApiController extends Controller
{
    public $layout = false;
    public $enableCsrfValidation = false;


    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {

            $offlineTime = Offline::offline();

            $H = date('H',time());
            $i = date('i',time());

            $fun= ((($offlineTime->start_h < $H) || ($offlineTime->start_h == $H && $offlineTime->start_i <= $i)) ||
                (($offlineTime->end_h > $H) || ($offlineTime->end_h == $H && $offlineTime->end_i > $i)));

            if ($fun) {
                if (yii::$app->request->post('is_api') == 0) {
                    return true;
                }

                header("Content-type: application/json");
                exit(json_encode([
                    'code' => 10001,
                    'timestamp' => time(),
                    'message' => '网站维护中',
                    'data' => ['offTime'=>$offlineTime->start.'-'.$offlineTime->end],
                ]));

            }
            return true;
        }

        return false;
    }

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
