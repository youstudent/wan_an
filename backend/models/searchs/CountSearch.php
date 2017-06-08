<?php

namespace backend\models\searchs;


use yii\base\Model;

class CountSearch extends Model
{
    public $start;  //开始日期
    public $end;    //结束日期

    //验证规则
    public function rules()
    {
        return [
            [['start','end'],'string'],
        ];
    }

    //字段名
    public function attributeLabels()
    {
        return [
          'start'=>'开始日期:',
          'end'=>'结束日期:'
        ];
    }

    //搜索
    public function search($query){
        $this->load(\Yii::$app->request->get());
        if ($this->start && $this->end){
            $start = strtotime($this->start);
            $end = strtotime($this->end);
            $query->andWhere(['>=','created_at',$start]);
            $query->andWhere(['<=','created_at',$end]);
        }
    }



}