<?php

namespace backend\controllers;

use Yii;
use backend\models\Branner;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use sintret\gii\models\LogUpload;
use sintret\gii\components\Util;
use yii\helpers\FileHelper;


/**
 * BrannerController implements the CRUD actions for Branner model.
 */
class BrannerController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Branner models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Branner::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Branner model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Branner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Branner();

        if ($model->load(Yii::$app->request->post())) {
            $model->img = UploadedFile::getInstance($model,'img');
            $path = '/upload/branner_imgs/';
            if ($model->img){
                if (!file_exists($path)){
//                    mkdir($path,'0777',true);
                    FileHelper::createDirectory($path, $mode = 0775, $recursive = true);
                }
                $path = $path.uniqid().'.'.$model->img->extension;
                $model->img->saveAs($path,false);
                $model->img=$path;
             }
            $model->save();
            Yii::$app->session->setFlash('info', '添加成功!');
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

/**
     * Updates an existing Branner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->img = UploadedFile::getInstance($model,'img');
            $path = '/upload/branner_imgs/';
            if ($model->img){
               if (!file_exists($path)){
                    mkdir($path,'0777',true);
                }
                $path = $path.uniqid().'.'.$model->img->extension;
                $model->img->saveAs($path,false);
                $model->img=$path;
            }else{
                $re = $this->findModel($id);
                $model->img=$re->img;
            }
            $model->save();
            Yii::$app->session->setFlash('info', '修改成功!');
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Branner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('danger', '删除成功!');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Branner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Branner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Branner::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionSample() {

        //$objPHPExcel = new \PHPExcel();
        $template = Util::templateExcel();
        $model = new Branner;
        $date = date('YmdHis');
        $name = $date.Branner;
        //$attributes = $model->attributeLabels();
        $models = Branner::find()->all();
        $excelChar = Util::excelChar();
        $not = Util::excelNot();
        
        foreach ($model->attributeLabels() as $k=>$v){
            if(!in_array($k, $not)){
                $attributes[$k]=$v;
            }
        }

        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load(Yii::getAlias($template));

        return $this->render('sample', ['models' => $models,'attributes'=>$attributes,'excelChar'=>$excelChar,'not'=>$not,'name'=>$name,'objPHPExcel' => $objPHPExcel]);
    }
    
    public function actionParsing() {
        $model = new LogUpload;
        $date = date('Ymdhis') . Yii::$app->user->identity->id;

        if (Yii::$app->request->isPost) {
            $model->fileori = UploadedFile::getInstance($model, 'fileori');

            if ($model->validate()) {
                $fileOri = Yii::getAlias(LogUpload::$imagePath) . $model->fileori->baseName . '.' . $model->fileori->extension;
                $filename = Yii::getAlias(LogUpload::$imagePath) . $date . '.' . $model->fileori->extension;
                $model->fileori->saveAs($filename);
            }
            $params = Util::excelParsing(Yii::getAlias($filename));
            $model->params = \yii\helpers\Json::encode($params);
            $model->title = 'parsing Branner';
            $model->fileori = $fileOri;
            $model->filename = $filename;

            $num = 0;
            $fields = [];
            $values = [];
            if ($params)
                foreach ($params as $k => $v) {
                    foreach ($v as $key => $val) {
                        if ($num == 0) {
                            $fields[$key] = $val;
                            $max = $key;
                        }

                        if ($num >= 3) {
                            $values[$num][$fields[$key]] = $val;
                        }
                    }
                    $num++;
                }
            if (in_array('id', $fields)) {
                $model->type = LogUpload::TYPE_UPDATE;
            } else {
                $model->type = LogUpload::TYPE_INSERT;
            }
            $model->values = \yii\helpers\Json::encode($values);
            if ($model->save()) {
                $log = 'log_Branner'. Yii::$app->user->id;
                Yii::$app->session->setFlash('success', 'Well done! successfully to Parsing data, see log on log upload menu! Please Waiting for processing indicator if available...  ');
                Yii::$app->session->set($log, $model->id);
                $notification = new \sintret\gii\models\Notification;
                $notification->title = 'parsing Branner';
                $notification->message = Yii::$app->user->identity->username . ' parsing Branner ';
                $notification->params = \yii\helpers\Json::encode(['model' => 'Branner', 'id' => $model->id]);
                $notification->save();
            }
        }
        $route = 'branner/parsing-log';

        return $this->render('parsing', ['model' => $model, 'array' => $array,'log'=>$log,'route'=>$route]);
    }
    
    public function actionParsingLog($id) {
        $mod = LogUpload::findOne($id);
        $type = $mod->type;
        $params = \yii\helpers\Json::decode($mod->params);
        $values = \yii\helpers\Json::decode($mod->values);
        $modelAttribute = new Branner;
        $not = Util::excelNot();
        foreach ($modelAttribute->attributeLabels() as $k=>$v){
            if(!in_array($k, $not)){
                $attr[] = $k;
            }
        }
            
            foreach ($values as $value) {
                if ($type == LogUpload::TYPE_INSERT)
                    $model = new Branner;
                else
                    $model = Branner::findOne($value['id']);

                foreach ($attr as $at) {
                    if (isset($value[$at])) {
                        if ($value[$at]) {
                            $model->$at = trim($value[$at]);
                        }
                    }
                }
                $e = 0;
                if ($model->save()) {
                    $model = NULL;
                    $pos = NULL;
                } else {
                    $error[] = \yii\helpers\Json::encode($model->getErrors());
                    $e = 1;
                }
            }

        if ($error) {
            foreach ($error as $err) {
                if ($err) {
                    $er[] = $err;
                    $e+=1;
                }
            }
            if ($e) {
                $mod->warning = \yii\helpers\Json::encode($er);
                $mod->save();
                echo '<pre>';
                print_r($er);
            }
        }
    }
    
    
   /* //处理图片异步上传
    public function actionImage ()
    {
        $p1 = $p2 = [];
        if (empty($_FILES)) {
            echo 111;
            //echo $_FILES['Banner']['img'];
            return;
        }
        for ($i = 0; $i < count($_FILES['Branner']); $i++) {
            $url = '/branner/deletes';
           // $imageUrl = $_FILES['Branner']['name']['img']['']; //调用图片接口上传后返回图片地址
// 图片入库操作，此处不可以批量直接入库，因为后面我们还要把key返回 便于图片的删除
            $model = new Branner();
            $model->img = $_FILES['Branner']['name']['img'][0];
            $key = 0;
            if ($model->save(false)) {
                $key = $model->id;
            }
// $pathinfo = pathinfo($imageUrl);
// $caption = $pathinfo['basename'];
// $size = $_FILES['Banner']['size']['banner_url'][$i];
           // $p1[$i] = $imageUrl;
            $p2[$i] = ['url' => $url, 'key' => $key];
        }
        echo json_encode([
            'initialPreview' => $p1,
            'initialPreviewConfig' => $p2,
            'append' => true,
        ]);
        return;
    }
    
    //删除图片上传
    public function actionDeletes ()
    {
        if ($id = Yii::$app->request->post('key')) {
            $model = $this->findModel($id);
            $model->delete();
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['success' => true];
    }*/
}
