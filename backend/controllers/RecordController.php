<?php

namespace backend\controllers;

use backend\tests\FunctionalTester;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Yii;
use backend\models\Record;
use backend\models\searchs\RecordSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use sintret\gii\models\LogUpload;
use sintret\gii\components\Util;


/**
 * RecordController implements the CRUD actions for Record model.
 */
class RecordController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['get'],
                ],
            ],
        ];
    }

    /**
     * Lists all Record models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RecordSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Record model.
     * @param string $id
     * @return mixed
     */
    /*public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }*/

    /**
     * Creates a new Record model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /*public function actionCreate()
    {
        $model = new Record();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Well done! successfully to save data!  ');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }*/

    /**
     * Updates an existing Record model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        Record::pass($id,1);
        return $this->redirect(['index',['status'=>0]]);
    }

    /**
     * Deletes an existing Record model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Record::pass($id,2);
        return $this->redirect(['index',['status'=>0]]);
    }
    
    
    
    //批量操作 通过
    public function actionBatch(){
        //接收数据
        $data = [1,2,3,4,6];
        Record::batch($data,1,4);
    }
    
    //批量操作 拒绝
    public function actionRefuse(){
        $data = [1,2,3,4,6];
        Record::batch($data,2,9);
        
    }
    
    
    

    /**
     * Finds the Record model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Record the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Record::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionSample() {

        //$objPHPExcel = new \PHPExcel();
        $template = Util::templateExcel();
        $model = new Record;
        $date = date('YmdHis');
        $name = $date.Record;
        //$attributes = $model->attributeLabels();
        $models = Record::find()->all();
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
            $model->title = 'parsing Record';
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
                $log = 'log_Record'. Yii::$app->user->id;
                Yii::$app->session->setFlash('success', 'Well done! successfully to Parsing data, see log on log upload menu! Please Waiting for processing indicator if available...  ');
                Yii::$app->session->set($log, $model->id);
                $notification = new \sintret\gii\models\Notification;
                $notification->title = 'parsing Record';
                $notification->message = Yii::$app->user->identity->username . ' parsing Record ';
                $notification->params = \yii\helpers\Json::encode(['model' => 'Record', 'id' => $model->id]);
                $notification->save();
            }
        }
        $route = 'record/parsing-log';

        return $this->render('parsing', ['model' => $model, 'array' => $array,'log'=>$log,'route'=>$route]);
    }
    
    public function actionParsingLog($id) {
        $mod = LogUpload::findOne($id);
        $type = $mod->type;
        $params = \yii\helpers\Json::decode($mod->params);
        $values = \yii\helpers\Json::decode($mod->values);
        $modelAttribute = new Record;
        $not = Util::excelNot();
        foreach ($modelAttribute->attributeLabels() as $k=>$v){
            if(!in_array($k, $not)){
                $attr[] = $k;
            }
        }
            
            foreach ($values as $value) {
                if ($type == LogUpload::TYPE_INSERT)
                    $model = new Record;
                else
                    $model = Record::findOne($value['id']);

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
}
