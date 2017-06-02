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
use yii\swiftmailer\Mailer;

class RegisterForm
{
    public $member_id;

    public function register($post, $referrer_id, $action_member_id = null)
    {
        //添加会员逻辑。 1.扣除推荐人两个豆子 。 2。 给操作人加 5块钱 3.添加会员。得到会员id . 4.查询推荐人信息，获取到座位  5. 添加座位，完成挂靠关系。 6.补全所有的区域信息， 7完成添加
        $member = $this->addMember($post, $referrer_id);

        //TODO::给操作人价钱


        //step1 . 判断推荐人是否区满
        $this->step1($referrer_id);
        echo 'success';

        //先写最普通的40人的排序
//        $count = District::find()->groupBy('member_id')->count();
//        if($count <= 40){
//            //所有会员都在一个区，不用判断区. 先获取用户的最大id
//            $max = District::find()->groupBy('member_id')->orderBy('seat desc')->one();
//
//            //根据映射图，找到玩家的座位
//            $new = Tree::$structure[$max['seat']+1];
//            if(isset($new) && !empty($new)){
//                //添加会员
//
//                $member = $this->addMember($post, $referrer_id);
//
//                //添加关联表
//                $re = new District();
//                $re->member_id = $member->id;
//                $re->district = 1;
//                $re->seat = $max['seat'] + 1;
//                $re->created_at = time();
//                $re->save();
//
//                //添加上一级的关联表
//
//            }
//
//        }
    }

    /**
     * 递归查询 推荐人所在区是否满

     * @param $referrer_id

     */
    public function step1($referrer_id)
    {
        //添加自身的区域
        //$this->addNode($this->member_id, $this->member_id, 1);

        //获取推荐人的可用id
        $referrer_districts = $this->getMemberDistrictSeatCount($referrer_id);



        echo '<pre>';
        //开始递归存放了
        $parent = Tree::$structure[$referrer_districts['num']+1];
        print_r($parent);
        echo '<hr>';

        //找上级节点
        do{
            $flag = true;
            //根据区域id 找到会员id
            $parent_member = $this->byDistrictsGetMemberId($referrer_districts['district']);
            print_r($parent_member);
            echo '<hr>';

            //给上级节点所在区域添加一个座位. 先找到
            $seat = $this->getDistrictSeatCount($parent_member['member_id']);
            print_r($seat);
            echo '<hr>';
            if($seat < 40){
                //$this->addNode($this->member_id, $parent_member['districts'], $seat+ 1);

                //获取父节点的memner_id

            }else{
                $flag = false;
            }
            $flag = false;

        } while($flag);

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
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getMemberRootDistrict($member_id)
    {
        return District::find()->where(['member_id'=>$member_id, 'seat'=>1])->one();
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
