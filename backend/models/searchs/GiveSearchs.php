<?php

namespace backend\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Give;

/**
 * GiveSearchs represents the model behind the search form about `common\models\Give`.
 */
class GiveSearchs extends Give
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'member_id', 'give_member_id', 'type', 'created_at'], 'integer'],
            [['give_coin'], 'number'],
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
        $query = Give::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'member_id' => $this->member_id,
            'give_member_id' => $this->give_member_id,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'give_coin' => $this->give_coin,
        ]);

        return $dataProvider;
    }
}
