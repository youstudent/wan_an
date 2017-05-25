<?php

namespace backend\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Member;

/**
 * MemberSearch represents the model behind the search form about `backend\models\Member`.
 */
class MemberSearch extends Member
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'site', 'parent_id', 'group_num', 'child_num', 'a_coin', 'b_coin', 'gross_income', 'gorss_bonus', 'last_login_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'password', 'mobile', 'deposit_bank', 'bank_account', 'address'], 'safe'],
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
        $query = Member::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'site' => $this->site,
            'parent_id' => $this->parent_id,
            'group_num' => $this->group_num,
            'child_num' => $this->child_num,
            'a_coin' => $this->a_coin,
            'b_coin' => $this->b_coin,
            'gross_income' => $this->gross_income,
            'gorss_bonus' => $this->gorss_bonus,
            'last_login_time' => $this->last_login_time,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'deposit_bank', $this->deposit_bank])
            ->andFilterWhere(['like', 'bank_account', $this->bank_account])
            ->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }
}
