<?php

namespace common\models;

use backend\models\Member;
use common\components\Helper;
use Symfony\Component\Debug\Tests\Fixtures\DeprecatedInterface;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%district}}".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $district
 * @property integer $seat
 * @property integer $created_at
 */
class District extends \yii\db\ActiveRecord
{
    public $errorMsg = '';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%district}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'district'], 'required'],
            [['member_id', 'district', 'seat', 'created_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'district' => 'District',
            'seat' => 'Seat',
            'created_at' => 'Created At',
        ];
    }

    /**
     * 获取一个简单的树状结构
     * @param $post
     * @param null $default_username
     * @return bool|mixed
     */
    public function simpleTree($post, $default_username = null)
    {
        $username = ArrayHelper::getValue($post, 'username', null);
        $up =  ArrayHelper::getValue($post, 'up', 0);
        if(empty($username)){
            if(is_null($default_username)){
                $this->errorMsg = '未获取到登陆信息';
                return false;
            }
            $username = $default_username;
        }

        $vip_number = Helper::username2VipNumber($default_username);

        //区分是向上还是向下
        if($up){
            $root_district = $this->getMemberParentDistrict($vip_number);
        }else{
            $root_district = $this->getMemberDistrict($vip_number);
        }
        if(!isset($root_district)){
            $this->errorMsg = '获取不到会员信息';
            return false;
        }
        //查询这个区域下所有的会员
        $data = $this->getDistrictMember($root_district['district'], 'm.username,d.seat');
        $output = $data[0];
        $i = 1;
        while(isset($data[$i])){
            $output['num'] = $i;
            $output['child'][] = $data[$i];
            $i++;
        }
        return $output;
    }

    /**
     * 获取完整的40人区
     * @param $post
     * @return array
     */
    public function getFullTree($post)
    {
        $username = ArrayHelper::getValue($post, 'username');
        $up =  ArrayHelper::getValue($post, 'up', 0);
        $is_api = ArrayHelper::getValue($post, 'is_api', 0);
        //找到这个vip的 base 区



        //是前端请求的接口，就要判断权限
        if($is_api == 1){
            $username = $username ? $username : ArrayHelper::getValue(Yii::$app->session->get('member'), 'username');
            $member_id = Helper::username2MemberId($username);
            if(empty($member_id)){

                $this->errorMsg = '会员不存在';
                return false;
            }
            //判断会员在不在挂靠区里面
            $login_member_id = ArrayHelper::getValue(Yii::$app->session->get('member'), 'member_id');
            if($login_member_id != $member_id && Helper::checkMemberIsUnder($login_member_id, $member_id) == false){
                $this->errorMsg = '无权限';
                return false;
            }
        }

        $vip_number = $username ? Helper::username2VipNumber($username) : 1;

        if($up){
            $district = $this->getMemberDistrict($vip_number, [2,3,4]);
        }else{
            $district = $this->getMemberDistrict($vip_number);
        }
        //找到这个区的所有会员
        $members = $this->getDistrictAllMember($district['district'], 'username,seat');
        //这里要根据座位号来排序了
        foreach($members as &$val){
            $val['pid'] = Tree::$structure[$val['seat']]['node'];
            $val['child'] = [];
        }

        //return $members;
        $tree = [];
        foreach($members as $member){
            if(isset($members[$member['pid']])){
                $members[$member['pid']]['child'][] = &$members[$member['seat']];
            }else{
                $tree[] = &$members[$member['seat']];
            }
        }

        return $tree;
    }

    /**
     * 更新会员的奖金资料
     * @param $old_member_id
     * @param $new_member_id
     * @return bool
     */
    public function modifyBonus($old_member_id, $new_member_id)
    {
        $flag = true;
        //变更用户的奖金记录到新的用户奖金记录下面
        $a_coin = Bonus::find()->where(['type'=>1, 'member_id'=> $old_member_id])->sum('num');

        $flag = $flag &&  Yii::$app->db->createCommand()->update('{{%bonus}}', ['member_id'=>$new_member_id], ['member_id'=>$old_member_id])->execute();

        //更新两个用户的奖金a
        $oldMember = Member::findOne(['id'=>$old_member_id]);
        $oldMember->a_coin = $oldMember->a_coin - $a_coin;
        //更新已被换位标志
        $oldMember->out_status = 1;
        $flag = $flag && $oldMember->save(false);

        $newMember = Member::findOne(['id'=>$new_member_id]);
        $newMember->a_coin += $a_coin;
        $flag = $flag && $newMember->save(false);
        return $flag;
    }
    /**
     * 交换两个会员的区位置
     * @param $old_member_id
     * @param $new_member_id
     * @return bool
     */
    public function changeDistrict($old_member_id, $new_member_id)
    {
        $oldModel = District::find()->where(['member_id' => $old_member_id])->all();
        $newModel = District::find()->where(['member_id' => $new_member_id])->all();
        $flag = true;
        foreach($oldModel as $old_district){
            $old_district->member_id = $new_member_id;
            $flag = $flag && $old_district->save(false);
        }
        foreach($newModel as $new_district){
            $new_district->member_id = $old_member_id;
            $flag = $flag && $new_district->save(false);
        }
        return $flag;
    }
    /**
     * 查询单个会员的信息
     * @param $vip_number
     * @param array $seat
     * @return array|bool
     */
    public function getMemberDistrict($vip_number, $seat = [1])
    {
        return (new \yii\db\Query())->from(self::tableName() . ' d')->where(['vip_number'=>$vip_number, 'seat'=>$seat])->innerJoin('{{%member}} m', 'm.id = d.member_id')->select('m.vip_number,d.*')->one();
    }


    /**
     * 找到指定区域指定的座位数据
     * @param $district
     * @param string $select
     * @param array $seat
     * @return array
     */
    public function getDistrictMember($district, $select = 'm.vip_number,d.*', $seat = [1,2,3,4])
    {
        return (new \yii\db\Query())->from(self::tableName() . ' d')->where(['seat'=>$seat, 'district'=>$district])->innerJoin('{{%member}} m', 'm.id = d.member_id')->select($select)->orderBy(['seat'=>SORT_ASC])->all();
    }

    /**
     * 获取指定区域的所有会员
     * @param $district
     * @param string $select
     * @return array
     */
    public function getDistrictAllMember($district, $select = 'm.vip_number,d.*'){
        return (new \yii\db\Query())->from(self::tableName() . ' d')->where(['district'=>$district])->innerJoin('{{%member}} m', 'm.id = d.member_id')->select($select)->orderBy(['seat'=>SORT_ASC])->indexBy('seat')->all();
    }

    /**
     *  找到最近的一个节点，即上翻的节点
     * @param $vip_number
     * @return array|bool
     */
    public function getMemberParentDistrict($vip_number)
    {
        return (new \yii\db\Query())->from(self::tableName() . ' d')->where('vip_number = :vip_number and seat >1 ', [':vip_number' => $vip_number])->innerJoin('{{%member}} m', 'm.id = d.member_id')->select('m.vip_number,d.*')->orderBy(['seat'=>SORT_ASC])->one();
    }


}
