<?php
/**
 * User: harlen-angkemac
 * Date: 2017/5/25 - 下午7:45
 *
 */

namespace backend\controllers;


use backend\models\Offline;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class OfflineController extends Controller
{
    public function actionIndex()
    {
        $model = Offline::findOne(1);
        $time = $model->offline($model);
        return $this->render('index', [
            'model' => $time,
        ]);
    }

    public function actionUpdate()
    {
        $model = Offline::findOne(1);

        if ($model->updateTime(Yii::$app->request->post(), $model)) {
            Yii::$app->session->setFlash('success', '修改成功');
            return $this->redirect(['index']);
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }

}