<?php
/**
 * Created by PhpStorm.
 * User: gba12
 * Date: 2017/6/1
 * Time: 10:13
 */

namespace backend\controllers;

use backend\models\Bonus;
use backend\models\searchs\CountSearch;
use yii\filters\VerbFilter;
use yii\web\Controller;

class CountController extends Controller
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
        //查询  充值金果的数量
        $seed_a = Bonus::find()->select("sum(num),type,coin_type");
        $search->search($seed_a);
        $num_seedA = $seed_a->andWhere(['coin_type'=>1,'type'=>6])->asArray()->all();
        //查询  扣除金果的数量
        $seed_b = Bonus::find()->select("sum(num),type,coin_type");
        $search->search($seed_b);
        $num_seedB = $seed_b->andWhere(['coin_type'=>1,'type'=>7])->asArray()->all();
        //查询  充值金种子的数量
        $seed = Bonus::find()->select("sum(num),type,coin_type");
        $search->search($seed);
        $num_seed = $seed->andWhere(['coin_type'=>2,'type'=>6])->asArray()->all();
        //查询  扣除金种子的数量
        $seed2 = Bonus::find()->select("sum(num),type,coin_type");
        $search->search($seed2);
        $num_seed2 = $seed2->andWhere(['coin_type'=>2,'type'=>7])->asArray()->all();
         //查询 注册人的金种子    总业绩=  注册人数x900(注册成功会扣除400的金果和500的金种子)
        $b_coin = Bonus::find()->select("sum(num),type,coin_type");
        $search->search($b_coin);
        $coin  = $b_coin->andWhere(['coin_type'=>2,'type'=>10])->asArray()->all();
        //$sql  = "SELECT SUM( num ) , TYPE , coin_type FROM  `wa_bonus`WHERE coin_type =1 GROUP BY TYPE ";
        //->orWhere(['coin_type'=>2,'type'=>6])
        //获得类型 1:绩效 2:分享 3:额外分享 4:提现 5:注册奖金 6:充值 7:扣除 8:赠送 9:提现返回 10:注册扣除',
        $num1 = 0;
        $num2 = 0;
        $num3 = 0;
        $num4 = 0;  //成功提现的总额
        $num5 = 0;
        $num7 = 0;
        $num8 = 0;
        $num10= 0;
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
                /* case 8:
                    $num8 =$row['sum(num)'];
                    break;*/
                case 9:
                    $num9=$row['sum(num)'];
                    break;
                case 10:
                    $num10=$row['sum(num)'];
                    break;
            }
       }
        //总业绩
        $coin = $num10+$coin[0]['sum(num)'];
        //$total_money   总结余
        $total_money = $num_seed[0]['sum(num)']-$num4;
        //充值金果总数和充值金种子总数
        $new_a = $num_seedA[0]['sum(num)'] - $num_seedB[0]['sum(num)'];
        $new_b = $num_seed[0]['sum(num)'] - $num_seed2[0]['sum(num)'];
//        $query = (new \yii\db\Query());
//        $num_a = $query->from(Bonus::tableName())->where([ 'coin_type' => 1, 'type' => 6])->sum('num');
//        $query = (new \yii\db\Query());
//        $num_b = $query->from(Bonus::tableName())->where([ 'coin_type' => 2, 'type' => 6])->sum('num');
//        $query = (new \yii\db\Query());
//        $numm_a = $query->from(Bonus::tableName())->where([ 'coin_type' => 1, 'type' => 7])->sum('num');
//        $query = (new \yii\db\Query());
//        $numm_b = $query->from(Bonus::tableName())->where([ 'coin_type' => 2, 'type' => 7])->sum('num');
//        $new_a = $num_a-$numm_a;
//        $new_b = $num_b-$numm_b;
        $data=['total_money'=>$total_money,'num4'=>$num4,'num5'=>$num5,'num10'=>$coin,'num2'=>$num2,'num1'=>$num1,'num3'=>$num3,'new_a'=>$new_a,'new_b'=>$new_b];
        return $this->render('index', ['search' => $search,'data'=>$data]);
    }
    
    
}