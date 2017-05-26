<?php

namespace backend\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Bonus;

/**
 * MemberSearch represents the model behind the search form about `backend\models\Member`.
 */
class BonusSearch extends Bonus
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id'], 'required'],
            [['type', 'id', 'member_id', 'coin_amount', 'coin_count', 'status', 'created_at', 'updated_at'], 'integer']
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
        $query = Bonus::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'member_id' => $this->member_id,
            'type' => $this->type,
            'coin_amount' => $this->coin_amount,
            'coin_count' => $this->coin_count,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);


        return $dataProvider;
    }
}
