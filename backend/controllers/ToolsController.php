<?php
/**
 * User: harlen-angkemac
 * Date: 2017/5/25 - 下午7:45
 *
 */

namespace backend\controllers;


use backend\models\Upload;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class ToolsController extends Controller
{
    public function actionUpload()
    {
        $model = new Upload();
        $id = $model->upload(Yii::$app->request->post());

        Yii::$app->response->format = Response::FORMAT_JSON;

        if($id){
            return ['code'=>1, 'message'=> 'success', 'data' => ['id'=>$id], 'timestamp'=>time()];
        }
        return ['code'=>0, 'message'=> 'error', 'data' => [], 'timestamp'=>time()];

    }
}