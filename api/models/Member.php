<?php

namespace api\models;

use common\components\Helper;
use common\models\District;
use Yii;
use yii\db\Query;
use yii\web\IdentityInterface;
use api\models\Session;
use yii\captcha\CaptchaAction;
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
 * @property integer $child_num  register_member_id
 * @property string $username
 * @property integer $register_member_id
 * @property integer $out_status
 * @property string $last_login_ip
 */
class Member extends \yii\db\ActiveRecord
{
    public $verifyCode;
    public $child;
    public $gross_income;
    public $gorss_bonus;
    public $group_num;
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
            [['parent_id', 'last_login_time', 'status', 'created_at', 'updated_at', 'vip_number', 'a_coin', 'b_coin', 'child_num', 'out_status', 'register_member_id'], 'integer'],
            [['vip_number', 'a_coin', 'b_coin', 'child_num', 'username', 'register_member_id'], 'required'],
            [['name', 'password', 'mobile', 'deposit_bank', 'bank_account', 'address'], 'string', 'max' => 255],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
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
            'verifyCode' => 'Verification Code',
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

        $arr = ['username', 'parent_id', 'name', 'mobile', 'deposit_bank', 'bank_account', 'address',
                'child_num', 'a_coin', 'b_coin'];
        $query = (new \yii\db\Query());
        $data= $query->select($arr)->from(Member::tableName())
                    ->where(['id' => $member_id])
                    ->one();
        if(!isset($data) || empty($data)){
            return null;
        }

        $data['child'] = Helper::getMemberUnderNum($member_id);
//        $child = 1;
        $data['group_num'] = Helper::getMemberUnderDistrict($member_id);
//        $group = 1;

        return $data;
    }
    /**
     * 直推数查询
     * @return int
     */
    public function son($id)
    {
        $query = (new \yii\db\Query());
        $num = $query->from(Member::tableName())->where(['parent_id' => $id])->count();

        return $num;
    }

    /**
     * 区数量查询
     * @return int
     */
    public function group($id, $num =0)
    {
        $query = (new \yii\db\Query());
        $district = $query->select('district')->from(District::tableName())->where(['member_id' => $id, 'seat' => 1])->one();

        $query = (new \yii\db\Query());
        $data = $query->select('member_id')->from(District::tableName())->where(['district' => $district['district']])->all();

        if (count($data) >= 40) {
            $num++ ;
            $data = array_splice($data,1);
            foreach ($data as $v) {
                $num = $this->group($v['member_id'], $num);
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
    /**
     * 用户登录操作
     * @param $id $password
     * @return bool|array
     */
    public function login($username, $password)
    {
        if(empty($username) || empty($password)){
            $this->addError('message', '账号或者密码不能为空');
            return false;
        }

        $detail = Member::findOne(['username'=>$username]);
        $query = new Query();
        $member = $query
            ->from(Member::tableName())
            ->where(['username'=>$username])
            ->one();
        if(!isset($detail) || !Member::validatePassword($password,$detail->password)){
            $this->addError('message', '账号或密码错误');
            return false;
        }
        if($member['status'] != 1){
            $this->addError('message', '请联系管理员');
            return false;
        }
        $detail->last_login_time = time();
        $detail->last_login_ip = Yii::$app->request->getUserIP();
        $detail->save(false);
        // session 保存用户登录数据
        Yii::$app->session->set('member',['member_id'=>$member['id'],'member_name'=>$member['name'], 'vip_number'=>$member['vip_number'], 'username' => $member['username']]);
        return true;

    }

    /**
     * 退出登录
     * @param
     * @return bool|array
     */
    public function logout()
    {
        Yii::$app->session->destroy();
        Yii::$app->session->removeAll();
        return true;
    }
    /**
     * 退网
     * @param
     * @return bool|array
     */
    public function outLine()
    {
        // 获取用户id
        $session = Yii::$app->session->get('member');
        $member_id = $session['member_id'];

        // 改变状态值
        $model = Member::findOne($member_id);
        if(!$model->out_status){
            $this->addError('message', '你现在还不能退网');
            return false;
        }
        if($model->status == 2){
            $this->addError('message', '你已经退网了');
            return false;
        }
        $model->status = 2;

        // 改变上一级直推数量
        $query = (new \yii\db\Query());
        $pid = $query->select('parent_id')->from(Member::tableName())->where(['id' => $member_id])->scalar();
        $query = (new \yii\db\Query());
        $pidModel = Member::findOne($pid);
        $pidModel->child_num = $pidModel->child_num - 1;

        // 添加退网记录
        $outModel = new Outline();
        $outModel->member_id = $model->id;
        $outModel->username = $model->username;
        $outModel->name = $model->name;
        $outModel->mobile = $model->mobile;
        $outModel->vip_number = $model->vip_number;
        $outModel->created_at = time();
        $outModel->updated_at = $model->created_at;
        $outModel->ext_data = json_encode($model->toArray(), JSON_UNESCAPED_UNICODE);

        if($model->save(false) && $pidModel->save(false) && $outModel->save(false)){
            return true;
        }
        return false;
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

        $session = Yii::$app->session->get('member');
        $detail = Member::findOne($member_id);
        if ($detail) {
            if ($data['name']==null) {
                $message = '姓名不能为空';
                $code = 0;
                $this->addError('code', $code);
                $this->addError('message', $message);
                return false;
            }
            $newmember = Member::findOne($member_id);
           // $newmember->name = $data['name'];
            $newmember->bank_account = $data['bank_account'];
            $newmember->deposit_bank = $data['deposit_bank'];
            $newmember->address = $data['address'];
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
        $data = Member::findOne(['username'=>$params['username']]);
        if(isset($data)){
            return ['name'=>$data->name];
        }
        $this->addError('message', '推荐人不存在');
        return false;
    }

    /**
     * 时段内禁止登陆
     * @param
     * @return bool|array
     */
    public function outTime()
    {
        if ( date('H')== 23 && date('i') >= 50) {
            return true;
        }
        if (date('H') >=0  && date('H') <= 8) {
            return true;
        }
        return false;

    }

    /**
     * 检查会员名是否可用
     * @param $params
     * @return bool
     */
    public function checkMember($params)
    {
        if(!preg_match("/\b[a-zA-Z0-9]{5}\b/", $params['username'])){
            return false;
        }
        $one = $this->getOne($params);

        if(isset($one) && !empty($one)){
            return false;
        }
        return true;
    }
}
