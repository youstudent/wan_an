<?php

namespace backend\models\searchs;

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

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        if(isset($params['status']) && $params['status'] == 1){
            $query->andFilterWhere([
                'status' => [1,2]
            ]);
           
        }else{
            $query->andFilterWhere([
                'status' => 0
                
            ]);
        }
        
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        /*$status='';
        if ($this->status =='待处理'){
            $status=0;
        }else if ($this->status=='已通过'){
            $status=1;
        }else if ($this->status=='已拒绝'){
            $status=2;
        }*/
        $query->andFilterWhere([
            'id' => $this->id,
            'member_id' => $this->member_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status'=>$this->status,
        ]);
        

        return $dataProvider;
    }
}
