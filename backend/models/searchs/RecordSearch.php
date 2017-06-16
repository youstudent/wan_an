<?php

namespace backend\models\searchs;

use backend\models\Member;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Record;

/**
 * searchs represents the model behind the search form about `backend\models\Record`.
 */
class recordSearch extends Record
{
    public $member_name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'member_id'], 'integer'],
            [['coin'], 'number'],
            [['status','created_at','updated_at'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Record::find();
        $query->joinWith(['member']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        if(isset($params['status']) && $params['status'] == 1){
            $query->andFilterWhere([
                self::tableName() .'.status' => [1,2]
            ]);

        }else{
            $query->andFilterWhere([
                self::tableName() .'.status' => 0

            ]);
        }

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        /*$status='';
        if ($this->status =='通过'){
            $status=1;
        }else if ($this->status=='拒绝'){
            $status=2;
        }*/
         $start='';
         $end = '';
        //格式化时间
        if ($this->created_at){
            $start_date = substr($this->created_at,0,10);
            $start = strtotime($start_date);
            $end_date =  substr($this->created_at,12);
            $end = strtotime($end_date);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            //'member_id' => $this->member_id,
            // 'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            '{{%record}}.status'=>$this->status,
            'coin'=>$this->coin
        ])->andFilterWhere(['>=','created_at',$start])->andFilterWhere(['<=','created_at',$end]);

       $query->andFilterWhere(['like', '{{%member}}.vip_number', $this->member_id]) ;
        return $dataProvider;
    }
}
