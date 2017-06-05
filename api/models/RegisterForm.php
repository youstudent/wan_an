<?php
/**
 * User: harlen-angkemac
 * Date: 2017/5/31 - 下午1:51
 *
 */

namespace api\models;


use common\models\District;
use common\models\Tree;
use Yii;
use yii\db\Exception;
use yii\swiftmailer\Mailer;

class RegisterForm extends Member
{
    public $member_id;
    public $errorMsg;

    public $referrer_id;
    public $action_member_id;
    public $re_password;




    public function rules()
    {
        return [
            [['referrer_id', 'name', 'mobile', 'password', 're_password', 'a_coin', 'b_coin'], 'required'],
            [['referrer_id', 'mobile', 'a_coin', 'b_coin'], 'integer'],
            [['deposit_bank', 'bank_account', 'address','name'], 'string'],
            ['a_coin', 'integer', 'max'=> 500, 'min'=> 0, 'tooBig'=> '{attribute}最多使用500'],
            ['b_coin', 'integer', 'max'=> 900, 'min'=> 400, 'tooSmall'=> '{attribute}最少使用400'],
            ['re_password', 'validateRePassword', 'skipOnEmpty' => false, 'skipOnError' => false],
            ['mobile','match','pattern'=>'/^0?(13|14|15|18)[0-9]{9}$/','message'=>'手机号码不正确']
        ];
    }

    public function attributeLabels()
    {
        return [
            'referrer_id'  => '分享人id',
            'name'         => '姓名',
            'mobile'       => '手机号',
            'password'     => '密码',
            're_password'  => '重复密码',
            'a_coin'       => '金果',
            'b_coin'       => '金种子',
            'address'      => '地址',
            'deposit_bank' => '开户行',
            'bank_account' => '银行账号',
        ];
    }

    /**
     * 验证密码是否一致
     * @param $attribute
     * @param $params
     */
    public function validateRePassword($attribute, $params)
    {
        if($this->re_password !== $this->password){
            $this->addError('password', '密码和重复密码不一致');
        }
    }

    public function register($post, $action_member_id)
    {
        if(!$this->load($post, '') || !$this->validate()){
            $this->errorMsg = current($this->getFirstErrors());
            return null;
        }
        //判断操作人的金钱是不是够
        $result = $this->rewardActionMember($action_member_id);
        if($result == false){
            $this->errorMsg = current($this->getFirstErrors());
            return null;
        }
        //$transaction = Yii::$app->db->beginTransaction();

        //获取
        $this->vip_number = Member::find()->count() + 1;
        $this->password = Yii::$app->security->generatePasswordHash($this->password);
        $member = $this->save();
        $this->member_id = $this->id;

        if ($member == false) {
            $this->errorMsg = '添加会员失败';
            //$transaction->rollBack();
            return false;
        }


        //step1 . 判断推荐人是否区满
        $result = $this->step1($this->referrer_id);
        if ($result == false) {
            //$transaction->rollBack();
            return false;
        }
        try {
            //$transaction->commit();
            return true;
        } catch (Exception $e) {
            //$transaction->rollBack();
            return false;
        }

    }

    /**
     * 操作之前，检查操作人是否有足够的金钱，并生成扣款记录和奖励记录
     * @param $member_id
     * @return bool
     */
    public function rewardActionMember($member_id)
    {
       $member = Member::findOne(['id'=>$member_id]);
       if($member->a_coin < $this->a_coin){
           $this->addError('a_coin', '金果数量不足');
           return false;
       }
       if($member->b_coin < $this->b_coin){
           $this->addError('b_coin', '金种子数量不足');
           return false;
       }
       $member->a_coin -= $this->a_coin + 5;
       $member->b_coin -= $this->b_coin;
       $member->save();

       //清空这两个记录的值
       $this->a_coin =0;
       $this->b_coin =0;
       //TODO::生成扣款记录和奖励记录
       return true;
    }
    /**
     * 添加相应的区域
     * @param $referrer_id
     * @return bool
     */
    public function step1($referrer_id)
    {
        //$transaction = Yii::$app->db->beginTransaction();
        //查找推荐人的id
        //添加自身的区域
        $district = $this->getMemberDistrict($referrer_id);
        $result = $this->addNode($this->member_id, $this->vip_number, 1);

        if ($result == false) {
            $this->errorMsg = '添加自身区域失败';
            //$transaction->rollBack();
            return false;
        }

        $result = $this->addDistrictSeat($referrer_id);

        if ($result == false) {
            $this->errorMsg = '添加父级区域失败';
            //$transaction->rollBack();
            return false;
        }
        return true;
    }

    /**
     * 添加会员到所有的区
     * @param $referrer_id
     * @return bool
     */
    public function addDistrictSeat($referrer_id)
    {
        //先收集需要的设置的id
        $affiliated_node = $this->getMemberDistrictSeatCount($referrer_id);
        //基础区域值,用这个区域，找到所有需要添加位置的member_id
        $base__district = $affiliated_node['district'];
        $affiliated_one = $this->byDistrictsGetMemberId($base__district);

        $seat = $affiliated_node['num'] + 1;
        //得到会员的member_id
        $node = [];
        $member_id = $this->member_id;
        while ($affiliated_one['member_id'] !== $member_id) {
            $seat_node = Tree::$structure[$seat];
            $seat = $seat_node['node'];
            $one = $this->byDistrictsGetMemberId($base__district, $seat);
            $seat_node['member_id'] = $member_id = $one['member_id'];
            $seat_node['district'] = $district = $this->getMemberRootDistrict($member_id);
            $seat_node['seat'] = $this->getDistrictSeatCount($district);

            $node[] = $seat_node;
            $result = $this->addNode($this->member_id, $seat_node['district'], $seat_node['seat'] + 1);
            if ($result == false) {
                return false;
            }
            //TODO::给分享人或者挂靠人价钱，这里要判断 。即是分享人也是挂靠人的特俗情况
        }
        return true;
    }

    /**
     * 从区域id和座位找到指定的会员id
     * @param $districts
     * @param int $seat
     * @return array|null|\yii\db\ActiveRecord
     */
    public function byDistrictsGetMemberId($districts, $seat = 1)
    {
        return District::find()->where(['district' => $districts, 'seat' => $seat])->one();
    }

    /**
     * 获取会员推荐的人，必要存放的区域
     * @param $member_id
     * @return null
     */
    public function getMemberDistrictSeatCount($member_id)
    {
        $district = $this->getMemberDistrict($member_id);
        $lists = (new \yii\db\Query())->from(District::tableName())->where(['district' => $district])->select('count(`id`) as num,id,district')->groupBy('district')->orderBy(['district' => SORT_ASC])->all();
        foreach ($lists as $list) {
            if ($list['num'] < 40) {
                return $list;
            }
        }
        return null;
    }

    /**
     * 返回会员的所有区域
     * @param $member_id
     * @return int|string
     */
    public function getMemberDistrict($member_id)
    {
        return District::find()->where(['member_id' => $member_id])->select('district')->orderBy(['district' => SORT_ASC])->column();
    }

    /**
     * 根据会员id。获取会员的root区
     * @param $member_id
     * @return false|null|string
     */
    public function getMemberRootDistrict($member_id)
    {
        return District::find()->where(['member_id' => $member_id, 'seat' => 1])->select('district')->scalar();
    }

    /**
     * 获取区域当前会员数量
     * @param $district
     * @return int|string
     */
    public function getDistrictSeatCount($district)
    {
        return District::find()->where(['district' => $district])->count();
    }

    /**
     * 添加会员
     * @param $post
     * @param $referrer_id
     * @return Member
     */
    public function addMember($post, $referrer_id)
    {
        $model = new Member();
        $model->name = $post['name'];
        $model->password = $post['password'];
        $model->created_at = time();
        $model->parent_id = $referrer_id;
        $model->child_num += 1;
        $model->mobile = $post['mobile'];
        $model->save();


        $this->member_id = $model->id;
        return $model;
    }

    /**
     * 添加一个节点记录
     * @param $member_id
     * @param $district
     * @param $seat
     * @param null $pos
     * @return District|null
     */
    public function addNode($member_id, $district, $seat, $pos = null)
    {
        $model = new District();
        $model->member_id = $member_id;
        $model->district = $district;
        $model->created_at = time();
        $model->seat = $seat;
        if(!$model->save()){
            $this->errorMsg = $model->getFirstErrors();
            return false;
        }
        return true;
    }

}
