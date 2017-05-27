<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/5/27
 * Time: 11:50
 */

namespace api\controllers;


use api\models\Announcements;
use api\models\Branner;
use Codeception\Module\REST;
use yii\web\Controller;

class AnnouncementController extends ApiController
{
    
    //公告 接口列表
    public function actionIndex(){
        $model = new Announcements();
        $data = $model->index();
        if($data){//如果返回的数据是true说明 申请成功
           return $this->jsonReturn(1, 'success',$data);
        }
        //如果返回false 返回错误信息
        return $this->jsonReturn(0, $model->getErrors('message')[0]);
        
    }
    
    
    //公告 详情页面
    public function actionSelect(){
        $model  = new Announcements();
        $data = $model->select(1);
        if($data){//如果返回的数据是true说明 申请成功
            return $this->jsonReturn(1, 'success',$data);
        }
        //如果返回false 返回错误信息
           return $this->jsonReturn(0, '数据不存在');
    }
    
    
    //广告  接口列表
    public function actionList(){
        $model  = new Branner();
        $data = $model->branner();
       
        if($data){//如果返回的数据是true说明 申请成功
            return $this->jsonReturn(1, 'success',$data);
        }
        //如果返回false 返回错误信息
            return $this->jsonReturn(0, '图片暂不存在');
    }
    
    //广告  详情
    public function actionListid(){
        $model  = new Branner();
        $data = $model->listid(4);
        if($data){//如果返回的数据是true说明 申请成功
            return $this->jsonReturn(1, 'success',$data);
        }
             //如果返回false 返回错误信息
           return $this->jsonReturn(0, '详情页面不存在');
    
    
    }
    
    
   
}