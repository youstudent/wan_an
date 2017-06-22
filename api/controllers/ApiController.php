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

            $start_H = $offlineTime->start_h < $H;
            $start_h = $offlineTime->start_h == $H;
            $start_i = $offlineTime->start_i <= $i;

            $end_H = $offlineTime->end_h > $H;
            $end_h = $offlineTime->end_h == $H;
            $end_i = $offlineTime->end_i >= $i;
            if (($offlineTime->start_h >= $offlineTime->end_h) && ($offlineTime->start_i > $offlineTime->end_i) ) {
                $close_site = ((($start_H) || ($start_h && $start_i)) || (($end_H) || ($end_h == $H && $end_i)));
            } else {
                $close_site = ((($start_H) || ($start_h && $start_i)) && (($end_H) || ($end_h == $H && $end_i)));
            }


            if ($close_site) {

                $post = yii::$app->request->post();
                if ($action->id == 'full-tree' && $this->id == 'member' && isset($post['is_api']) && $post['is_api'] == 1) {
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
