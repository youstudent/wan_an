<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/6/1
 * Time: 10:13
 */

namespace backend\controllers;

use backend\models\Bonus;
use backend\models\form\UploadForm;
use backend\models\Member;
use backend\models\Record;
use backend\models\searchs\CountSearch;
use Codeception\Module\REST;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use yii\web\Controller;

class CountController extends Controller
{
    //后台统计中心
    public function actionIndex()
    {
        //实例化搜索 From
        $search = new CountSearch();
        //根据条件查询流水表数据
        $query = Bonus::find();
        $search->search($query);
        $rows = $query->all();
        //获得类型 1:绩效 2:分享 3:额外分享 4:提现 5:注册奖金 6:充值 7:扣除 8:赠送 9:产生5元奖励 11:注册会员人数
        $num1 = '';
        $num2 = '';
        $num3 = '';
        $num4 = '';
        $num5 = '';
        $num6 = '';
        $num7 = '';
        $num8 = '';
        foreach ($rows as $row) {
            //循环数据   根据类型区分
            switch ($row->type) {
                case 1:
                    $num1 += $row->num;
                    continue;
                case 2:
                    $num2 += $row->num;
                    continue;
                case 3:
                    $num3 += $row->num;
                    continue;
                case 4:
                    $num4 += $row->num;
                    continue;
                case 5:
                    $num5 += $row->num;
                    continue;
                case 6:
                    $num6 += $row->num;
                    continue;
                case 7:
                    $num7 += $row->num;
                    continue;
                case 8:
                    $num8 += $row->num;
                    continue;
            }
        }
        var_dump($num8, $num1);
        return $this->render('index', ['search' => $search]);
    }
    
    
    //图片上传
    public function actionTest(){
        $model= new UploadForm();
        
        return $this->render('test',['model'=>$model]);
        
    }
    
    
}