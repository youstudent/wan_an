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

class RegisterForm
{
    public $member_id;
    public $errorMsg;

    public function register($post, $referrer_id, $action_member_id = null)
    {
        $transaction = Yii::$app->db->beginTransaction();
        //TODO:: 给操作人加奖金
        $member = $this->addMember($post, $referrer_id);

        if($member == false){
            $this->errorMsg = '添加会员失败';
            $transaction->rollBack();
            return false;
        }
        //TODO::给操作人价钱


        //step1 . 判断推荐人是否区满
        $result = $this->step1($referrer_id);
        if($result == false){
            $this->errorMsg = '座位寻找错误';
            $transaction->rollBack();
            return false;
        }
        try{
            $transaction->commit();
            return true;
        }catch(Exception $e){
            $transaction->rollBack();
            return false;
        }

    }

    /**
     * 递归查询 推荐人所在区是否满

     * @param $referrer_id

     */
    public function step1($referrer_id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        //添加自身的区域
        $result = $this->addNode($this->member_id, $this->member_id, 1);
        if($result == false){
            $this->errorMsg = '添加自身区域失败';
            $transaction->rollBack();
            return false;
        }

        $result = $this->addDistrictSeat($referrer_id);
        if($result == false){
            $this->errorMsg = '添加父级区域失败';
            $transaction->rollBack();
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
        while($affiliated_one['member_id'] !== $member_id){
            $seat_node = Tree::$structure[$seat];
            $seat = $seat_node['node'];
            $one = $this->byDistrictsGetMemberId($base__district, $seat);
            $seat_node['member_id'] = $member_id = $one['member_id'];
            $seat_node['district'] = $district = $this->getMemberRootDistrict($member_id);
            $seat_node['seat']  = $this->getDistrictSeatCount($district);

            $node[] = $seat_node;
            $result = $this->addNode($this->member_id, $seat_node['district'], $seat_node['seat'] + 1);
            if($result == false){
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
    public function byDistrictsGetMemberId($districts, $seat = 1){
        return District::find()->where(['district' => $districts, 'seat'=> $seat])->one();
    }
    /**
     * 获取会员推荐的人，必要存放的区域
     * @param $member_id
     * @return null
     */
    public function getMemberDistrictSeatCount($member_id)
    {
        $district = $this->getMemberDistrict($member_id);
        $lists = (new \yii\db\Query())->from(District::tableName())->where(['district'=>$district])->select('count(`id`) as num,id,district')->groupBy('district')->orderBy(['district'=> SORT_ASC])->all();
        foreach($lists as $list){
            if($list['num'] < 40){
                return $list;
            }
        }
        return null;
    }
    /**
     * 返回会员所有区的id
     * @param $member_id
     * @return array
     */
    public function getMemberDistrict($member_id)
    {
        return District::find()->where(['member_id'=>$member_id])->select('district')->orderBy(['district'=> SORT_DESC])->column();
    }

    /**
     * 根据会员id。获取会员的root区
     * @param $member_id
     * @return false|null|string
     */
    public function getMemberRootDistrict($member_id)
    {
        return District::find()->where(['member_id'=>$member_id, 'seat'=>1])->select('district')->scalar();
    }

    /**
     * 获取区域当前会员数量
     * @param $district
     * @return int|string
     */
    public function getDistrictSeatCount($district)
    {
        return District::find()->where(['district'=>$district])->count();
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
        $model->child_num +=1;
        $model->mobile = $post['mobile'];
        $model->save();


        $this->member_id = $model->id;
        return $model;
    }
    public function addNode($member_id, $district, $seat, $pos = null)
    {
        $model = new District();
        $model->member_id = $member_id;
        $model->district = $district;
        $model->created_at = time();
        $model->seat = $seat;

        return $model->save() ? $model : null;
    }

}
