<?php

namespace backend\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Outline;

/**
 * OutlineSearch represents the model behind the search form about `backend\models\Outline`.
 */
class OutlineSearch extends Outline
{
    public $name;
    public $mobile;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'member_id', 'status', 'updated_at'], 'integer'],
            [['created_at'], 'safe'],
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
        $query = Outline::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

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
            'member_id' => $this->member_id,
            'member.name' => $this->name,
            'member.mobile' => $this->mobile,
            'status' => $this->status,
//            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ])->andFilterWhere(['>=','created_at',$start])->andFilterWhere(['<=','created_at',$end]);

        return $dataProvider;
    }
}
