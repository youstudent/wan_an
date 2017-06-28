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
    public $member_username;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'member_id'], 'integer'],
            [['coin'], 'number'],
            [['status','created_at','updated_at', 'member_username', 'member_name'],'safe']
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
        $query->alias('record');
        $query->joinWith(['member']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        if(isset($params['status']) && $params['status'] == 1){
            $query->andFilterWhere([
                'record.status' => [1,2]
            ]);

        }else{
            $query->andFilterWhere([
                'record.status' => 0

            ]);
        }

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

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
            'record.updated_at' => $this->updated_at,
            'record.status'=>$this->status,
            'coin'=>$this->coin
        ])->andFilterWhere(['>=','record.created_at',$start])->andFilterWhere(['<=','record.created_at',$end]);

        $query->andFilterWhere(['like', 'member.username', $this->member_username]) ;
        $query->andFilterWhere(['like', 'member.name', $this->member_name]) ;
        return $dataProvider;
    }
}
