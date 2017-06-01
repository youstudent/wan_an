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

    public function register($post, $referrer_id, $action_member_id = null)
    {
        //添加会员逻辑。 1.扣除推荐人两个豆子 。 2。 给操作人加 5块钱 3.添加会员。得到会员id . 4.查询推荐人信息，获取到座位  5. 添加座位，完成挂靠关系。 6.补全所有的区域信息， 7完成添加

        //step1 . 判断推荐人是否区满
        $this->step1($referrer_id);


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
        //获取推荐人所在的所有区
        $district = $this->getMemberDistrict($referrer_id);
        //获取这些人的区
        $statuss = (new \yii\db\Query())->from(District::tableName())->where(['in', 'district', $district])->select('count(`id`) as num,id,district')->groupBy('district')->orderBy('district asc')->all();

        //从高位获取，找到第一个 未满40人的区。并在哪里安排下会员
        foreach($statuss as $status){
           if($status['num'] < 40){
               //在这里给会员安家
               $member_district = $status['district'];
               //找到这个区最近的一个座位
               $seat = (new \yii\db\Query())->from(District::tableName())->where(['district'=> $member_district])->orderBy('seat desc')->select('seat')->scalar() + 1;
               //根据这个座位在映射图寻找挂靠领导信息及自身的位置信息
               $pos = Tree::$structure[$seat];
               //保存这个这个位置信息
               $pos['node'];
               $pos['position'];
               //添加到座位关系表中
           }
        }



    }

    /**
     * 返回会员所有区的id
     * @param $member_id
     * @return array
     */
    public function getMemberDistrict($member_id)
    {
        return District::find()->where(['member_id'=>$member_id])->select('district')->orderBy('district desc')->column();
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

        return $model;
    }

    /**
     * 生成自身到顶点所有区域的记录。单线祖先的记录。最长4条，在1已经被初始化的情况下，最短是2
     * @param $member_id
     * @param $seat 自身的座位号
     * @param $top_seat 订单的座位号
     */
    public function setDistrict($member_id, $referrer_id, $seat, $top_seat, $district)
    {
        $data = [];
        while($seat !== $top_seat){
            $data[] = [$member_id, $district, $seat, time()];
            //$referrer_id = $this->get
        }

    }


    public function getParentNodeDistrict($member_id)
    {

    }

}
