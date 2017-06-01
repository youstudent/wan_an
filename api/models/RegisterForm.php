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
        //先写最普通的40人的排序
        $count = District::find()->groupBy('member_id')->count();
        if($count <= 40){
            //所有会员都在一个区，不用判断区. 先获取用户的最大id
            $max = District::find()->groupBy('member_id')->orderBy('seat desc')->one();

            //根据映射图，找到玩家的座位
            $new = Tree::$structure[$max['seat']+1];
            if(isset($new) && !empty($new)){
                //添加会员

                $member = $this->addMember($post, $referrer_id);

                //添加关联表
                $re = new District();
                $re->member_id = $member->id;
                $re->district = 1;
                $re->seat = $max['seat'] + 1;
                $re->created_at = time();
                $re->save();

                //自身的 。 上一级的 上二级的 顶点的
                $this->setDistrict($member->id, $max['seat'] + 1, $re->district);
            }

        }
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
