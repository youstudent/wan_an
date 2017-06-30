<?php

namespace backend\models\searchs;

use backend\models\Bonus;
use common\components\Helper;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Member;

/**
 * MemberSearch represents the model behind the search form about `backend\models\Member`.
 */
class MemberSearch extends Member
{

    public $referrer_username;
    public $register_username;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'last_login_time', 'status','updated_at', 'vip_number', 'a_coin', 'b_coin', 'child_num', 'out_status'], 'integer'],
            [['name', 'password', 'mobile', 'deposit_bank', 'bank_account', 'address','created_at', 'referrer_username', 'register_username'], 'safe'],
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
        $query->alias('member');
        $query->joinWith('referrer');
        $query->joinWith('register');

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
          //  'parent_id' => $this->getParentId(),
            'last_login_time' => $this->last_login_time,
            'status' => $this->status,
//            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'vip_number' => $this->vip_number,
            'a_coin' => $this->a_coin,
            'b_coin' => $this->b_coin,
            'child_num' => $this->child_num,
        ])->andFilterWhere(['>=','created_at',$start])->andFilterWhere(['<=','created_at',$end]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'member.mobile', $this->mobile])
            ->andFilterWhere(['like', 'deposit_bank', $this->deposit_bank])
            ->andFilterWhere(['like', 'bank_account', $this->bank_account])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'referrer.username', $this->referrer_username])
            ->andFilterWhere(['like', 'register.username', $this->register_username]);

        return $dataProvider;
    }
}
