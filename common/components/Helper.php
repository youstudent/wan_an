<?php

namespace common\components;


use api\models\Member;
use common\models\Bonus;
use common\models\District;
use common\models\MemberDistrict;
use common\models\MemberNode;
use common\models\ShareLog;
use Yii;

class Helper{
    /**
     * 生成会员奖金记录
     * @param $member_id
     * @param $coin_type    1:金果  2:金种子
     * @param $type   1:绩效 - 挂靠  2:分享 - 推荐   3:额外分享 - 额外推荐 4:提现  5:注册奖金 - 5 块钱 6:充值 7:扣除 8:赠送 9:提现返回 10:注册扣除
     * @param $num
     * @param int $poundage
     * @param array $ext_data
     * @return Bonus|null
     */
    public static function saveBonusLog($member_id,$coin_type,$type,$num,$poundage=0,$ext_data= [])
    {
        $data = [
            'member_id' => $member_id,
            'coin_type' => $coin_type,
            'type' => $type,
            'num' => $num,
            'created_at' => time(),
            'updated_at' => time(),
            'poundage' => $poundage,
            'ext_data' => json_encode($ext_data, JSON_UNESCAPED_UNICODE)
        ];
        $model = new Bonus();

        return $model->load($data, '') && $model->save(false) ? $model : null;
    }

    /**
     * 添加分享会员记录 - 算直推区额外奖需要
     * @param $referrer_id
     * @param $member_id
     * @param null $time
     * @return ShareLog|null
     */
    public static function shareMemberLog($referrer_id, $member_id, $time = null)
    {
        $data = [
            'referrer_id' => $referrer_id,
            'member_id' => $member_id,
            'created_at' => $time ? $time : time()
        ];
        $model = new ShareLog();
        $model->load($data, '');
        return ($model->load($data, '') && $model->save(false)) ? $model : null;
    }

    /**
     * 添加会员直推区记录
     * @param $member_id
     * @param $district
     * @param $is_extra
     * @return MemberDistrict|null
     */
    public static function memberDistrictLog($member_id, $district, $is_extra)
    {
        $data = [
            'member_id' => $member_id,
            'district' => $district,
            'is_extra' => $is_extra,
        ];
        $model = new MemberDistrict();
        return ($model->load($data, '') && $model->save(false)) ? $model : null;
    }

    /**
     * 给会员添加B奖金
     * @param $member_id
     * @param $num
     * @return bool
     */
    public static function addMemberBCoin($member_id, $num)
    {
        $model = Member::findOne(['id'=>$member_id]);
        $model->b_coin +=$num;
        if(!$model->save(false)){
            return false;
        }
        return true;
    }
    /**
     * 给会员添加A奖金
     * @param $member_id
     * @param $num
     * @return bool
     */
    public static function addMemberACoin($member_id, $num)
    {
        $model = Member::findOne(['id'=>$member_id]);
        $model->a_coin +=$num;
        if(!$model->save(false)){
            return false;
        }
        return true;
    }

    /**
     * vip_number转member_id
     * @param $vip_number
     * @return int
     */
    public static function vipNumberToMemberId($vip_number)
    {
        $model = Member::findOne(['vip_number'=>$vip_number]);
        return $model->id;
    }

    /**
     * member_id转vip_number
     * @param $member_id
     * @return int
     */
    public static function memberIdToVipNumber($member_id)
    {
        if($member_id == 0){
            return '不存在';
        }
        $model = Member::findOne(['id'=>$member_id]);
        return $model->vip_number;
    }

    /**
     * 添加会员的NODE表关系
     * @param $member_id
     * @return bool
     */
    public static function addMemberNode($member_id)
    {

        //先根据Member_id 找到对应的上级id
        $parent_district = District::findOne(['member_id'=>$member_id, 'seat'=>[2,3,4]])->district;

        $parent_id  = District::findOne(['district'=>$parent_district, 'seat'=>1])->member_id;

        $model  = new MemberNode();
        $model->member_id = $member_id;
        $model->above_member_id = $parent_id;
        if(!$model->save()){
            return false;
        }
        //获取上级的所有，并继承过来
        $parentMemberNode = MemberNode::find()->select( 'above_member_id')->where(['member_id'=>$parent_id])->orderBy(['id'=>SORT_ASC])->asArray()->all();
        if(isset($parentMemberNode) && count($parentMemberNode)){
            foreach($parentMemberNode as &$val){
                $val[] = $member_id;
            }
            $result = Yii::$app->db->createCommand()->batchInsert(MemberNode::tableName(), ['above_member_id', 'member_id'], $parentMemberNode)->execute();
            if(!$result){
                return false;
            }
        }
        return true;
    }

    /**
     * 获取玩家的挂靠数
     * @param $member_id
     * @return int|string
     */
    public static function getMemberUnderNum($member_id)
    {
        return MemberNode::find()->where(['member_id'=>$member_id])->count();
    }

    /**
     * 获取玩家的满区数量
     * @param $member_id
     * @return int|string
     */
    public static function getMemberUnderDistrict($member_id)
    {
        $member_ids  = MemberNode::find()->where(['member_id'=>$member_id])->select('above_member_id')->column();

        if(isset($member_id) && count($member_ids)){
            //获取区数量
            return District::find()->where(['member_id'=>$member_ids, 'seat'=>40])->count();
        }
        return 0;
    }

    /**
     * 用户名转换成memberid
     * @param $username
     * @return mixed
     */
    public static function username2MemberId($username)
    {
        return Member::findOne(['username'=>$username])->id;
    }
}
