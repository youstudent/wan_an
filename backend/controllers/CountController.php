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
use function PHPSTORM_META\type;
use yii\web\Controller;

class CountController extends Controller
{
    //后台统计中心
    public function actionIndex()
    {
        //实例化搜索 From
        $search = new CountSearch();
        //根据条件查询流水表数据
        $total = Bonus::find()->select('sum(num),type,coin_type');
        $search->search($total);
        $num_total = $total->andWhere(['coin_type'=>1])->groupBy('type')->asArray()->all();
        //var_dump($num_total);
        
        //查询  充值经金种子的数量
        $seed = Bonus::find()->select("sum(num),type,coin_type");
        $search->search($seed);
        $num_seed = $seed->andWhere(['coin_type'=>2,'type'=>6])->asArray()->all();
        //var_dump($num_seed);
        //$sql  = "SELECT SUM( num ) , TYPE , coin_type FROM  `wa_bonus`WHERE coin_type =1 GROUP BY TYPE ";
        //->orWhere(['coin_type'=>2,'type'=>6])
        //var_dump($rows);
        //获得类型 1:绩效 2:分享 3:额外分享 4:提现 5:注册奖金 6:充值 7:扣除 8:赠送 9:提现返回 10:注册扣除',
        $num1 = '';
        //var_dump($num1);exit;
        $num2 = '';
        $num3 = '';
        $num4 = '';  //成功提现的总额
        $num5 = '';
        $num7 = '';
        $num8 = '';
        $num10= '';
        //总结余
        //var_dump($num_seed);exit;
       
       foreach ($num_total as $row) {
          
            //循环数据   根据类型区分
            switch ($row['type']) {
                case 1:
                    $num1 =$row['sum(num)'];
                    break;
                case 2:
                    $num2 =$row['sum(num)'];
                    break;
                case 3:
                    $num3 =$row['sum(num)'];
                    break;
                case 4:
                    $num4 =$row['sum(num)'];
                    break;
                case 5:
                    $num5 =$row['sum(num)'];
                    break;
                case 7:
                    $num7 =$row['sum(num)'];
                    break;
                case 8:
                    $num8 =$row['sum(num)'];
                    break;
                case 9:
                    $num9=$row['sum(num)'];
                    break;
                case 10:
                    $num10=$row['sum(num)'];
                    break;
            }
       }
        //$total_money   总结于
        $total_money = $num_seed[0]['sum(num)']-$num4;
        $data=['total_money'=>$total_money,'num4'=>$num4,'num5'=>$num5,'num10'=>$num10,'num2'=>$num2,'num1'=>$num1,'num3'=>$num3];
        return $this->render('index', ['search' => $search,'data'=>$data]);
    }
    
    
    
    
}