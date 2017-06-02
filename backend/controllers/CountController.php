<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/6/1
 * Time: 10:13
 */

namespace backend\controllers;

use backend\models\Bonus;
use backend\models\Member;
use backend\models\Record;
use backend\models\searchs\CountSearch;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use yii\web\Controller;

class CountController extends Controller
{
    //后台统计中心
    public function actionIndex(){
        $search = new CountSearch();
        $query = Bonus::find();
        // 1.>>总业绩  总的会员*900
        $count=Member::find()->count()*900;
        // 2.>>财务总支出
        //$coin=Record::find()->where(['status'=>1])->all();
        //$c='';
        //foreach ($coin as $v){
           //c+=$v['coin'];
       // }
        $search->search($query);
        // 3.>> 结余
        
        return $this->render('index',['count'=>$count,'c'=>1,'search'=>$search]);
    }
    
    
    //搜索
    public function actionSearchs(){
    
    
    
    }
    
}