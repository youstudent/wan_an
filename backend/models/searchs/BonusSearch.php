<?php

namespace backend\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Bonus;

/**
 * BonussSearch represents the model behind the search form about `backend\models\Bonus`.
 */
class BonusSearch extends Bonus
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'member_id', 'coin_type', 'type', 'num', 'poundage'], 'integer'],
            [['ext_data','created_at','updated_at'], 'safe'],
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
    public function search($params, $id)
    {
        $query = Bonus::find()->where(['member_id' => $id, 'coin_type' => 1, 'type' => [1,2,3,5]]);

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
            'coin_type' => $this->coin_type,
            'type' => $this->type,
//            'created_at' => $this->created_at,
        ])->andFilterWhere(['>=','created_at',$start])->andFilterWhere(['<=','created_at',$end]);

        return $dataProvider;
    }
}
