<?php

namespace backend\controllers;

use Yii;
use backend\models\Deposit;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use sintret\gii\models\LogUpload;
use sintret\gii\components\Util;


/**
 * DepositController implements the CRUD actions for Deposit model.
 */
class DepositController extends Controller
{

    public function behaviors()
    {
        return parent::behaviors();
        return [
        'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','view','sample','parsing-log'],
                        'roles' => ['viewer']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update','create','parsing'],
                        'roles' => ['editor']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['admin']
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Deposit models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Deposit::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Deposit model.
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
     * 充值
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionOpen()
    {
        $model = new Deposit();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($model->deposit(Yii::$app->request->post())) {
                Yii::$app->session->setFlash('success', '充值成功');
            }
        }
        return $this->render('deposit', [
            'model' => $model,
        ]);
    }

    /**
     * 扣除
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionEnd()
    {
        $model = new Deposit();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->deposit(Yii::$app->request->post())) {
                Yii::$app->session->setFlash('success', '扣除成功');
            }
        }

        return $this->render('deduct', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Deposit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->session->setFlash('success', 'Well done! successfully to update data!  ');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Deposit model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Well done! successfully to deleted data!  ');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Deposit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Deposit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Deposit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionSample() {

        //$objPHPExcel = new \PHPExcel();
        $template = Util::templateExcel();
        $model = new Deposit;
        $date = date('YmdHis');
        $name = $date.Deposit;
        //$attributes = $model->attributeLabels();
        $models = Deposit::find()->all();
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
            $model->title = 'parsing Deposit';
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
                $log = 'log_Deposit'. Yii::$app->user->id;
                Yii::$app->session->setFlash('success', 'Well done! successfully to Parsing data, see log on log upload menu! Please Waiting for processing indicator if available...  ');
                Yii::$app->session->set($log, $model->id);
                $notification = new \sintret\gii\models\Notification;
                $notification->title = 'parsing Deposit';
                $notification->message = Yii::$app->user->identity->username . ' parsing Deposit ';
                $notification->params = \yii\helpers\Json::encode(['model' => 'Deposit', 'id' => $model->id]);
                $notification->save();
            }
        }
        $route = 'deposit/parsing-log';

        return $this->render('parsing', ['model' => $model, 'array' => $array,'log'=>$log,'route'=>$route]);
    }
    
    public function actionParsingLog($id) {
        $mod = LogUpload::findOne($id);
        $type = $mod->type;
        $params = \yii\helpers\Json::decode($mod->params);
        $values = \yii\helpers\Json::decode($mod->values);
        $modelAttribute = new Deposit;
        $not = Util::excelNot();
        foreach ($modelAttribute->attributeLabels() as $k=>$v){
            if(!in_array($k, $not)){
                $attr[] = $k;
            }
        }
            
            foreach ($values as $value) {
                if ($type == LogUpload::TYPE_INSERT)
                    $model = new Deposit;
                else
                    $model = Deposit::findOne($value['id']);

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
