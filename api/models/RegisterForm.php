<?php
/**
 * User: harlen-angkemac
 * Date: 2017/5/31 - 下午1:51
 *
 */

namespace api\models;


use common\components\Helper;
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
            ['mobile','match','pattern'=>'/^0?(13|14|15|18)[0-9]{9}$/','message'=>'手机号码不正确'],

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
            return false;
        }

        if($this->a_coin + $this->b_coin != 900){
            $this->errorMsg = '注入的金豆和金种子和必须等于900';
            return false;
        }

        //判断操作人的金钱是不是够
        $result = $this->rewardActionMember($action_member_id);
        if($result == false){
            $this->errorMsg = current($this->getFirstErrors());
            return false;
        }
        //$transaction = Yii::$app->db->beginTransaction();


        //添加会员
        $result = $this->addMember($post);
        if ($result == false) {
            $this->errorMsg = '添加会员失败';
            //$transaction->rollBack();
            return false;
        }
        $this->member_id = $result->id;




        //给分享人添加区数和奖金记录
        $this->setIncMemberShare($this->referrer_id);

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
     * 设置直推数量的递增
     * @param $referrer_id
     * @return bool
     */
    public function setIncMemberShare($referrer_id)
    {
        $model = Member::findOne(['id'=>$referrer_id]);
        $model->child_num+=1;
        return $model->save(false);

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

        //生成奖励5块钱的流水
        Helper::saveBonusLog($member_id, 5, 1, 5);
        //添加消耗记录
        if($this->a_coin){
            Helper::saveBonusLog($member_id, 1, 10, $this->a_coin);
        }
        Helper::saveBonusLog($member_id, 2, 10, $this->b_coin);

        //清空这两个记录的值
        $this->a_coin =0;
        $this->b_coin =0;

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
        //根据推荐人 member_id 获取到推荐人的所有区，并查询这个区的人数。再返回人数未满的第一个区
        $affiliated_node = $this->getMemberDistrictSeatCount($referrer_id);


        //获取会员的摆放区域
        $base_district = $affiliated_node['district'];
        //找到这个区域root会员信息
        $affiliated_one = $this->byDistrictsGetMemberId($base_district);

        //会员的座位号，就是这个基础区人数 + 1
        $seat = $affiliated_node['num'] + 1;


        //得到会员的member_id
        $node = [];
        $member_id = $this->member_id;
        //记录一个循环次数
        $i = 1;
        while ($affiliated_one['member_id'] !== $member_id) {
            //获取添加会员座位的上级座位节点信息
            $parent_seat_node = Tree::$structure[$seat];

            //上级的座位号就等于座位信息里面的座位号
            $seat = $parent_seat = $parent_seat_node['node'];
            //根据上级会员在区域里的座位号查询到上级的基础信息
            $parent_one = $this->byDistrictsGetMemberId($base_district, $parent_seat);
            $seat_node['member_id'] = $member_id = $parent_one['member_id'];
            $seat_node['district'] = $district = $this->getMemberRootDistrict($member_id);
            $seat_node['seat'] =  $this->getDistrictSeatCount($district);

            $node[] = $seat_node;
            $result = $this->addNode($this->member_id, $seat_node['district'], $seat_node['seat'] + 1);
            if ($result == false) {
                return false;
            }
            //第一次循环就找到爹了。 这个就只用给推荐人发一个直推奖励
            if($i == 1 && $seat_node['member_id'] == $referrer_id){
                $coin_money = Yii::$app->params['coin_type_2_money'];
                Helper::saveBonusLog($referrer_id, 1, 2, $coin_money, 0);
            }else{
                //给这个区所有的上级给见点奖
                $coin_money = Yii::$app->params['coin_type_1_money'];
                Helper::saveBonusLog($member_id, 1, 1, $coin_money, 0);
            }
            $i++;
        }
        //添加这个会员，然后就区满的情况，要进行满区逻辑
        if($affiliated_node['num'] = 40){
            $result = $this->actionFullDistrict($base_district);
            if($result == false){
                //TODO::回滚
                return false;
            }
        }

        return true;
    }


    //满区逻辑
    public function actionFullDistrict($district)
    {
        //TODO:: 1 检查换位条件 2. 给根会员添加有一个区数量保存 3.判断顶级会员的直推人的区数量。并执行直推区奖励

        //判断根会员是否要被换位
        $district_info = $this->byDistrictsGetMemberId($district);
        //获取这个根会员的基本信息
        $member = Member::findOne(['id'=>$district_info['member_id']]);
        if(!isset($member)){
            $this->errorMsg = '满区逻辑-未查询到当前区拥有这';
            return false;
        }
        //满足无直推会员，进行换位逻辑
        if($member->child_num == 0){
            //TODO::换位
        }

        //这里要添加直推区，就在这里判断一下直推区吧
        $referrer = Member::findOne(['id'=>$member->parent_id]);

        //给会员的推荐人添加一个直推区记录
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
     * 根据人 member_id 获取所有区，并查询这个区的人数。再返回人数未满的第一个区
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
     * 返回会员的所有区域，并按从旧区到新区的排列
     * @param $member_id
     * @return int|string|array
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
    public function addMember($post)
    {
        $model = new Member();
        $model->name = $post['name'];
        $model->created_at = time();
        $model->updated_at = time();
        $model->parent_id = $post['referrer_id'];
        $model->mobile = $post['mobile'];
        $model->vip_number = $this->vip_number = Member::find()->max('vip_number') + 1;
        $model->password = Yii::$app->security->generatePasswordHash($this->password);
        return $model->save(false) ? $model : null;
    }

    /**
     * 添加一个节点记录
     * @param $member_id
     * @param $district
     * @param $seat
     * @param null $pos
     * @return District|null|bool
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
