<?php

namespace api\models;

use Yii;
use yii\db\Query;
use yii\web\IdentityInterface;
use api\models\Session;
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
 */
class Member extends \yii\db\ActiveRecord
{
    public $child;
    public $gross_income;
    public $gorss_bonus;
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
            [['parent_id', 'last_login_time', 'status', 'created_at', 'updated_at', 'vip_number', 'a_coin', 'b_coin', 'child_num'], 'integer'],
            [['vip_number', 'a_coin', 'b_coin', 'child_num'], 'required'],
            [['name', 'password', 'mobile', 'deposit_bank', 'bank_account', 'address'], 'string', 'max' => 255],
            ['status', 'in', 'range' => [0,1]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '会员自增ID',
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
        ];
    }

    /**
     * 获取一个会员资料
     * 算法思路：
     *  1.查询会员是否存在
     *  2.查询会员的相关资料
     *  3.查询会员的附属资料
     * @param string $member_id
     * @return array
     */

    public function getOneMember()
    {
        // 获取用户id
        $session = Yii::$app->session->get('member');
        $member_id = $session['member_id'];
        // 测试
        $member_id = 2;
        if (!$this->validate()) {
           return null;
        }

        $arr = ['id', 'parent_id', 'name', 'mobile', 'deposit_bank', 'bank_account', 'address',
                'child_num', 'a_coin', 'b_coin'];
        $query = (new \yii\db\Query());
        $data= $query->select($arr)->from(Member::tableName())
                    ->where(['id' => $member_id])
                    ->one();
        if(!isset($data) || empty($data)){
            return null;
        }
        $data['child'] = 100;
        return $data;
    }

    /**
     * 用户登录操作
     * @param $id $password
     * @return bool|array
     */
    public function login($id, $password)
    {
        if(empty($id) || empty($password)){
            $this->addError('message', '账号或者密码不能为空');
            return false;
        }

        $detail = Member::findOne($id);
        $query = new Query();
        $member = $query
            ->from(Member::tableName())
            ->where(['id'=>$id])
            ->one();
        if(!isset($detail) || !Member::validatePassword($password,$detail->password)){
            $this->addError('message', '账号或密码错误');
            return false;
        }
        if($member['status'] != 1){
            $this->addError('message', '请联系管理员');
            return false;
        }
        // session 保存用户登录数据
        Yii::$app->session->set('member',['member_id'=>$id,'member_name'=>$member['name']]);
        $session = Yii::$app->session->get('member');
        return true;


    }

    /**
     * 退出登录
     * @param
     * @return bool|array
     */
    public function logout()
    {
//        Yii::$app->session->clear();
        // 获取用户id
        $session = Yii::$app->session->get('member');
        $member_id = $session['member_id'];
        // 测试
        $member_id = 2;
        $model = Member::findOne($member_id);
        $model->last_login_time = time();
        $model->save();
        Yii::$app->session->destroy();
        Yii::$app->session->removeAll();
        return true;
    }

    /**
     * 会员资料修改
     * @param
     * @return bool|array
     */

    public function updateDetail($data)
    {
        // 获取用户id
        $session = Yii::$app->session->get('member');
        $member_id = $session['member_id'];
        // 测试
        $member_id = 2;

        $session = Yii::$app->session->get('member');
        $detail = Member::findOne($member_id);
        if ($detail) {
            $newmember = Member::findOne($member_id);
            $newmember->name = $data['name']?$data['name']:$newmember->name;
            $newmember->bank_account = $data['bank_account']?$data['bank_account']:$newmember->bank_account;
            $newmember->deposit_bank = $data['deposit_bank']?$data['deposit_bank']:$newmember->deposit_bank;
            $newmember->address = $data['address']?$data['address']:$newmember->address;
            $newmember->updated_at=time();
            $newmember->save(false);
            return true;
        }


        return false;
    }
    /**
     * 密码修改
     * @param
     * @return bool|array
     */

    public function updatePass($data)
    {
        // 获取用户id
        $session = Yii::$app->session->get('member');
        $member_id = $session['member_id'];
        // 测试
        $member_id = 2;
        $detail = Member::findOne($member_id);
        if (false === $this->validatePassword($data['password'],$detail->password)) {
            $this->addError('message', '原密码不正确');
            return false;
        }
        if ($data['newPassword'] !== $data['rePassword']) {
            $this->addError('message', '两次密码输入不一致');
            return false;
        }
        if ($detail) {
            $newMember = Member::findOne($member_id);
            $newMember->password = Yii::$app->security->generatePasswordHash($data['newPassword']);
            $newMember->updated_at=time();
//            var_dump($newMember);die;
            $newMember->save(false);
        }
        return true;
    }
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password,$rePassword)
    {
        return Yii::$app->security->validatePassword($password, $rePassword);

    }

    /**
     * 获取单个会员记录
     * @param $params
     * @return array|bool
     */
    public function getOne($params)
    {
        $data = Member::findOne(['vip_number'=>$params['vip_number']]);
        if(isset($data)){
            return ['name'=>$data->name];
        }
        $this->addError('message', '推荐人不存在');
        return false;
    }

}
