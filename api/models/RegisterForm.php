<?php
/**
 * User: harlen-angkemac
 * Date: 2017/5/31 - 下午1:51
 *
 */

namespace api\models;


use common\components\Helper;
use common\models\District;
use common\models\DistrictChangeLog;
use common\models\MemberDistrict;
use common\models\ShareLog;
use common\models\Tree;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\swiftmailer\Mailer;

class RegisterForm extends Member
{
    public $member_id;
    public $errorMsg;

    public $referrer_id;
    public $action_member_id;
    public $re_password;

    protected $transaction;




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

    /**
     * 解锁
     * @return bool
     */
    protected function unLock()
    {
        $key = Yii::$app->params['lock_file_key'];
        return  Yii::$app->cache->set($key, 0);
    }

    /**
     * 检查锁
     * @return bool
     */
    protected function checkLock()
    {
        $key = Yii::$app->params['lock_file_key'];
        $lock = Yii::$app->cache->get($key);
        if($lock){
            return false;
        }
        Yii::$app->cache->set($key, 1, 3000);
        return true;
    }
    public function register($post, $action_member_id)
    {
        //$action_member_id=1;
        //将推荐的vip_number转换成member_id
        $post['referrer_id'] = $this->vipNumber2MemberId($post['referrer_id']);

        if(!$this->load($post, '') || !$this->validate()){
            $this->errorMsg = current($this->getFirstErrors());
            return false;
        }

        if($this->a_coin + $this->b_coin != 900){
            $this->errorMsg = '注入的金豆和金种子和必须等于900';
            return false;
        }

        if($this->checkLock() == false){
            $this->errorMsg = '后台正在进行注册操作，请稍后再试';
            return false;
        }

        $this->transaction = Yii::$app->db->beginTransaction();
        try{
            //判断操作人的金钱是不是够
            $result = $this->rewardActionMember($action_member_id);
            if($result == false){
                $this->errorMsg = current($this->getFirstErrors());
                $this->transaction->rollBack();
                $this->unLock();
                return false;
            }


            //检查有没有退网的空位
            $blank_member = $this->getBlankMemberInfo();
            if(isset($blank_member) && !empty($blank_member)){
                //这里进行继承会员
                return $this->inheritanceMember($post, $blank_member);
            }

            //添加会员
            $result = $this->addMember($post);
            if ($result == false) {
                $this->errorMsg = '添加会员失败';
                //$transaction->rollBack();
                $this->transaction->rollBack();
                $this->unLock();
                return false;
            }
            $this->member_id = $result->id;
            Helper::shareMemberLog($this->referrer_id, $this->member_id);

            //给分享人添加区数和奖金记录
            $result = $this->setIncMemberShare($this->referrer_id);
            if($result == false){
                $this->errorMsg = '添加分享人区奖金失败';
                $this->transaction->rollBack();
                $this->unLock();
                return false;
            }

            //step1 . 判断推荐人是否区满
            $result = $this->step1($this->referrer_id);
            if ($result == false) {
                //$transaction->rollBack();
                $this->transaction->rollBack();
                $this->unLock();
                return false;
            }
            $this->transaction->commit();
            $this->unLock();
            return true;
        }catch(Exception $exception){
            $this->transaction->rollBack();
            $this->unLock();
            return false;
        }
    }

    /**
     * 会员vip_number转换从成id
     * @param $vip_number
     * @return int|null
     */
    public function vipNumber2MemberId($vip_number)
    {
        $member = Member::findOne(['id'=>$vip_number]);
        return isset($member) ? $member->id : null;
    }
    //继承会员逻辑
    public function inheritanceMember($post, $blank_member)
    {
        //更改原先会员资料
        $blank_member->name = $post['name'];
        $blank_member->updated_at = time();
        $blank_member->parent_id = $post['referrer_id'];
        $blank_member->mobile = $post['mobile'];
        $blank_member->status=1;
        $blank_member->vip_number = $this->vip_number = Member::find()->max('vip_number') + 1;
        $blank_member->password = Yii::$app->security->generatePasswordHash($this->password);

        //更新扩展资料
        $blank_member->deposit_bank = ArrayHelper::getValue($post, 'deposit_bank', '');
        $blank_member->bank_account = ArrayHelper::getValue($post, 'bank_account', '');
        $blank_member->address = ArrayHelper::getValue($post, 'address', '');

        if(!$blank_member->save(false)){
            $this->errorMsg = '继承失败';
            $this->unLock();
            return false;
        }

        //添加一个推荐人奖励-
        $result = Helper::addMemberACoin($this->referrer_id, Yii::$app->params['coin_type_2_money']);
        if($result == false){
            $this->errorMsg = '添加分享人奖金失败';
            $this->unLock();
            return false;
        }
        return $this;
    }
    /**
     * 获取一个空白的会员信息
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getBlankMemberInfo()
    {
        return Member::find()->where(['status'=>2])->orderBy(['id'=>SORT_ASC])->one();
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
        if(!$model->save(false)){
            $this->errorMsg = '添加直推数量或者直推奖金失败';
            return false;
        }

        $coin_money = Yii::$app->params['coin_type_2_money'];
        Helper::saveBonusLog($referrer_id, 1, 2, $coin_money);
        //给推荐人发放奖金
        if(!Helper::addMemberACoin($referrer_id, $coin_money)){
            $this->errorMsg = '分享奖金添加失败';
            return false;
        }

        return true;

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
       $member->a_coin -= $this->a_coin;
       $member->a_coin += 5;
       $member->b_coin -= $this->b_coin;
       if($member->save(false) == false){
           $this->errorMsg = '操作人信息更新失败';
           return false;
       }

        //生成奖励5块钱的流水
        Helper::saveBonusLog($member_id, 1, 5, 5);
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
        //添加自身区域。 这里本来想获取一下id区域id的。没找到办法，就用vip_number来客串一下。所有后面的逻辑就要坑好多了
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

        $affiliated_node = $this->getAbleDistrict($referrer_id);
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

            //如果新增的会员，在上级的区里面，就没得 见点奖
            if( !($this->checkMemberInMemberDistrict($this->member_id, $referrer_id) && $member_id == $referrer_id) ){
                //给上级添加挂靠奖A
                $coin_money = Yii::$app->params['coin_type_1_money'];
                Helper::saveBonusLog($member_id, 1, 1, $coin_money, 0);
                if(!Helper::addMemberACoin($member_id, $coin_money)){
                    $this->errorMsg = '绩效奖金添加失败';
                    return false;
                }
            }

            $i++;
        }
        //添加这个会员，然后就区满的情况，要进行满区逻辑
        if($affiliated_node['num'] == 39){
            $result = $this->actionFullDistrict($base_district);
            if($result == false){
                //TODO::回滚
                return false;
            }
        }

        return true;
    }

    /**
     * 检查会员有没有在另一会员的区中
     * @param $member_id
     * @param $another_member_id
     * @return bool
     */
    public function checkMemberInMemberDistrict($member_id, $another_member_id)
    {
        $district = $this->getMemberRootDistrict($another_member_id);
        $result = $this->getDistrictMember($district, $member_id);

        return $result ? true : false;
    }

    /**
     * 获取指定区，指定会员信息
     * @param $district
     * @param $member_id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getDistrictMember($district, $member_id)
    {
        return District::find()->where(['district'=>$district, 'member_id'=>$member_id])->one();
    }
    /**
     * 根据推荐id。获取一个可用的区域信息
     * @param $referrer_id
     * @return null
     */
    public function getAbleDistrict($referrer_id)
    {
        //根据推荐人 member_id 获取到推荐人的所有区，并查询这个区的人数。再返回人数未满的第一个区
        $affiliated_node = null;
        while(is_null($affiliated_node)){
            //找到这会员的可用下级
            $affiliated_node = $this->getMemberDistrictSeatCount($referrer_id);
            $referrer_id = $this->getChildRandomDistrictMemberId($referrer_id);
            if($affiliated_node['num'] == 40){
                $affiliated_node = null;
            }
        }
        return $affiliated_node;
    }

    /**
     * 获取会员下面三个点，随机的一个的会员id
     * @param $member_id
     * @return bool|mixed
     */
    public function getChildRandomDistrictMemberId($member_id)
    {
        $member_district = $this->getMemberRootDistrict($member_id);
        $ids = $this->byDistrictsGetAllMemberId($member_id);
        if(!isset($ids) || empty($ids)){
            return false;
        }
        return $ids[array_rand($ids, 1)];

    }
    //满区逻辑
    public function actionFullDistrict($district)
    {
        //TODO:: 1 检查换位条件 2. 给根会员添加有一个区数量保存 3.判断顶级会员的直推人的区数量。并执行直推区奖励

        //添加直推区
        $result = $this->addReferrerChildDistrict($district);
        if($result == false){
            $this->errorMsg = '添加直推区失败';
            return false;
        }

        //判断 2.3.4位置的要不要被换位
        $member_ids = $this->byDistrictsGetAllMemberId($district);
        //获取这个根会员的基本信息
        $members = Member::find()->where(['id'=> $member_ids])->all();
        if(!isset($members)){
            $this->errorMsg = '满区逻辑-未查询到当前区拥有这';
            return false;
        }
        //满足无直推会员，进行换位逻辑
        foreach($members as $member) {
            if ($member->child_num == 0) {
                //检查换位条件.找到自己这个区有条件换位的会员
                $new_member_id = $this->getMemberDistrictMaxReferrerMember($member->id);
                if ($new_member_id !== false) {
                    //开始执行换位逻辑
                    //step1 对换区表中的位置
                    $district_model = new District();
                    $result = $district_model->changeDistrict($member->id, $new_member_id);
                    if ($result == false) {
                        $this->errorMsg = '交换区失败';

                        return false;
                    }
                    $result = $district_model->modifyBonus($member->id, $new_member_id);
                    if ($result == false) {
                        $this->errorMsg = '继承奖金失败';

                        return false;
                    }
                    if(DistrictChangeLog::addLog($member->id, $new_member_id) == false){
                        $this->errorMsg = '添加交换记录失败';

                        return false;
                    }
                }
            }
        }

        return true;

    }

    /**
     * 给刚生成40人的区的拥有人的推荐人添加一个区记录
     * @param $district
     * @return bool
     */
    public function addReferrerChildDistrict($district)
    {
        $root_member = $this->byDistrictsGetMemberId($district, [1]);
        //获取区主人。并给当前区加一个普通区记录
        $root_member_info = Member::findOne(['id'=>$root_member['member_id']]);
        Helper::memberDistrictLog($root_member_info->id, $district, 0);

        //判断区主人的推荐人是否存在
        if(!$root_member_info->parent_id){
            return true;
        }

        $is_extra = 0;
        $share_logs = ShareLog::find()->where(['referrer_id'=>$root_member_info->parent_id])->orderBy(['created_at'=> SORT_ASC])->all();
        if(isset($share_logs) && !empty($share_logs)){
            foreach($share_logs as $key => $val)
            {
                if($val['member_id'] == $root_member_info->id && $key > 2){
                    $is_extra = 1;
                    break;
                }
            }
        }
        Helper::memberDistrictLog($root_member_info->parent_id, $district, $is_extra);
        //执行额外分享逻辑
        if($is_extra){
            $this->addReferrerDistrictBonus($root_member_info->parent_id);
        }
        return true;

    }
    /**
     * 获取满足换位的会员
     * @param $member_id
     * @return bool|mixed
     */
    public function getMemberDistrictMaxReferrerMember($member_id)
    {
        $root_district = $this->getMemberRootDistrict($member_id);
        $ids = $this->byDistrictsGetAllMemberId($root_district, range(2,13));
        return $this->getMemberMaxReferrerMember($ids);
    }

    /**
     * 获取指定member_ids中。绩效最高的那位
     * @param $member_ids
     * @return bool|mixed
     */
    public function getMemberMaxReferrerMember($member_ids)
    {
        $member = Member::find()->where(['id'=>$member_ids])->orderBy(['child_num'=>SORT_DESC, 'created_at'=>SORT_ASC])->one();
        if(isset($member) && !empty($member)){
            if($member->child_num > 0){
                return $member->id;
            }
        }
        return false;
    }
    /**
     * 添加额外分享奖励
     * @param $member_id
     * @return \common\models\Bonus|null
     */
    public function addReferrerDistrictBonus($member_id)
    {
        $count = MemberDistrict::find()->where(['member_id'=>$member_id, 'is_extra'=>1])->count();
        if($count == 1){
            Helper::addMemberACoin($member_id, 300);
            return Helper::saveBonusLog($member_id, 1, 3, 300, 0, ['note'=> '额外分享第4个区']);
        }
        if($count == 2){
            Helper::addMemberACoin($member_id, 600);
            return Helper::saveBonusLog($member_id, 1, 3, 600, 0, ['note'=> '额外分享第5个区']);
        }else{
            Helper::addMemberACoin($member_id, 900);
            return Helper::saveBonusLog($member_id, 1, 3, 900, 0, ['note'=> '额外分享6个区,几6个区以上']);
        }
    }
    /**
     * 从区域id和座位找到指定的会员的信息
     * @param $districts
     * @param int $seat
     * @return array|null|\yii\db\ActiveRecord
     */
    public function byDistrictsGetMemberId($districts, $seat = 1)
    {
        return District::find()->where(['district' => $districts, 'seat' => $seat])->one();
    }

    /**
     * 从区域id获取指定会员的member_id
     * @param $districts
     * @param array $seat
     * @return array
     */
    public function byDistrictsGetAllMemberId($districts, $seat = [2,3,4])
    {
        return District::find()->where(['district' => $districts, 'seat' => $seat])->select('member_id')->column();
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
     * @return Member
     */
    public function addMember($post)
    {
        $model = new Member();
        $model->load($post, '');
        $model->created_at = time();
        $model->updated_at = time();
        $model->a_coin = 0;
        $model->b_coin = 0;
        $model->parent_id = $post['referrer_id'];
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
