<?php

namespace backend\models;

use Yii;
use backend\models\Bonus;

/**
 * This is the model class for table "wa_member".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property string $password
 * @property string $mobile
 * @property string $deposit_bank
 * @property string $bank_account
 * @property string $address
 * @property integer $last_login_time
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $vip_number
 * @property integer $a_coin
 * @property integer $b_coin
 * @property integer $child_num
 * @property integer $out_status
 */
class Member extends \yii\db\ActiveRecord
{
    public $msg;
    public $state;
    public $group_num;
    public $child;
    public $parent_vip;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'last_login_time', 'status', 'created_at', 'updated_at', 'vip_number', 'a_coin', 'b_coin', 'child_num', 'vip_number','out_status'], 'integer'],
            [['vip_number', 'a_coin', 'b_coin', 'child_num'], 'required'],
            [['name', 'password', 'mobile', 'deposit_bank', 'bank_account', 'address'], 'string', 'max' => 255]
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '会员ID',
            'parent_id' => '直推会员id',
            'name' => '用户姓名',
            'password' => '会员密码',
            'mobile' => '电话',
            'deposit_bank' => '开户行',
            'bank_account' => '银行账号',
            'address' => '地址',
            'last_login_time' => '最后登录时间',
            'status' => '状态',
            'created_at' => '创建时间 注册时间 入网时间',
            'updated_at' => '更新时间 退网时间',
            'vip_number' => '会员卡号',
            'a_coin' => '金果数',
            'b_coin' => '金种子数',
            'child_num' => '直推数量',
            'group_num' => '区数量',
            'child' => '区数量',
            'parent_vip' => '直推会员卡号',
        ];
    }


    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }
    /**
    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->createDate = date('Y-m-d H:i:s');
            $this->userCreate = Yii::$app->user->id;
            $this->userUpdate = Yii::$app->user->id;
        } else {
            $this->updateDate = date('Y-m-d H:i:s');
            $this->userUpdate = Yii::$app->user->id;
        }
        return parent::beforeSave($insert);
    }
    
    public function getUserCreateLabel() {

        $user = User::find()->select('username')->where(['id' => $this->userCreate])->one();
        return $user->username;
    }

    public function getUserUpdateLabel() {
        $user = User::find()->select('username')->where(['id' => $this->userUpdate])->one();
        return $user->username;
    }

    */

    /**
     * @param $param
     * @return bool|null
     */
    public function changeMember($param){
        $this->msg = $param['state'] == 0 ? '冻结' : '解冻';
        $info = Member::findOne(['id'=>$param['id']]);
        $info->status = $param['state'];

        return $info->save(false);
    }
    // 资金
    public function getBonus($num, $id)
    {
        if ($num == 1) {
            $query = (new \yii\db\Query());
            $old = $query->select('a_coin')->from(Member::tableName())->where(['id' => $id])->scalar();
            return $old?$old:0;
        }
        if ($num == 2) {
            $query = (new \yii\db\Query());
            $old = $query->select('b_coin')->from(Member::tableName())->where(['id' => $id])->scalar();
            return $old?$old:0;
        }
        if ($num == 3) {
            $query = (new \yii\db\Query());
            $old = $query->from(Bonus::tableName())->where(['member_id' => $id, 'coin_type' => 1 ,'type' => [1,2,3,5,6,11]])->sum('num');
            return $old?$old:0;
        }
        if ($num == 4) {
            $query = (new \yii\db\Query());
            $cover = $query->from(Bonus::tableName())->where(['member_id' => $id, 'coin_type' => 1, 'type' => 4])->sum('num');
            $back = $query->from(Bonus::tableName())->where(['member_id' => $id, 'coin_type' => 1, 'type' => 9])->sum('num');
            return $cover-$back?$cover-$back:0;
        }

    }
    // 1直推数 2区数量 3直系挂靠会员数
    public function getChild($num, $id)
    {
        if ($num == 1) {
            $query = (new \yii\db\Query());
            $child_num = $query->from(Member::tableName())->where(['parent_id' => $id])->count();
            return $child_num;
        }
        if ($num == 2) {
//            $group = 1;
            $group = $this->group($id);
            return $group;
        }
        if ($num == 3) {
//            $child = 1;
            $child = $this->child($id);
            return $child;
        }

    }
    protected $num = 0;

    /**
     * 区数量查询
     * @return int
     */
    public function group($id, $num =0)
    {
        $query = (new \yii\db\Query());
        $i = $query->select('district')->from(District::tableName())->where(['seat' => 1])->all();
        static $j;
        $j = 0;
        $query = (new \yii\db\Query());
        $district = $query->select('district')->from(District::tableName())->where(['member_id' => $id, 'seat' => 1])->one();

        $query = (new \yii\db\Query());
        $data = $query->select('member_id')->from(District::tableName())->where(['district' => $district['district']])->all();
        while ($j <= count($i)) {

            if (count($data) >= 40) {
                $num = 1;
                foreach ($data as $v) {
                    $query = (new \yii\db\Query());
                    $district = $query->select('district')->from(District::tableName())->where(['member_id' => $v['member_id'], 'seat' => 1])->one();

                    $query = (new \yii\db\Query());
                    $data = $query->select('member_id')->from(District::tableName())->where(['district' => $district['district']])->all();
                    if (count($data) >= 40) {
                        $num++ ;
                        $j++;
                        foreach ($data as $vv) {
                            $query = (new \yii\db\Query());
                            $district = $query->select('district')->from(District::tableName())->where(['member_id' => $vv['member_id'], 'seat' => 1])->one();

                            $query = (new \yii\db\Query());
                            $data = $query->select('member_id')->from(District::tableName())->where(['district' => $district['district']])->all();
                            if (count($data) >= 40) {
                                $num++ ;
                                $j++;
                            }
                        }
                    }
                }
            }

        }
        return $num;
    }

    /**
     * 挂靠总量查询
     * @return int
     */
    public function child($id, $num = 0)
    {
        $query = (new \yii\db\Query());
        $district = $query->select('district')->from(District::tableName())->where(['member_id' => $id, 'seat' => 1])->one();

        $query = (new \yii\db\Query());
        $data = $query->select('member_id')->from(District::tableName())->where(['district' => $district['district']])->all();

        $num = count($data)-1;
        $temp = 0;
        if (count($data) >= 40) {
            $data = array_splice($data,13);
            foreach ($data as $v) {
                $temp += $this->child($v['member_id'], 0);
            }
        }
        return $num + $temp;
    }

    public function one($id){
        if (($model = Member::findOne($id)) !== null) {
            $model->getVip();
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     * 获取parent卡号
     */
    public function getVip()
    {
        $parent = Member::findOne(['id' => $this->parent_id]);

        return $this->parent_vip = $parent['vip_number'];

    }
}
