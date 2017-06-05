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
            [['id', 'member_id', 'coin_type', 'type', 'num', 'created_at', 'updated_at', 'poundage'], 'integer'],
            [['ext_data'], 'safe'],
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

        $query->andFilterWhere([
            'id' => $this->id,
            'member_id' => $this->member_id,
            'coin_type' => $this->coin_type,
            'type' => $this->type,
            'num' => $this->num,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'poundage' => $this->poundage,
        ]);

        $query->andFilterWhere(['like', 'ext_data', $this->ext_data]);

        return $dataProvider;
    }
}
