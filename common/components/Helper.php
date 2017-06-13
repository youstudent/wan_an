<?php

namespace common\components;


use api\models\Member;
use common\models\Bonus;
use common\models\MemberDistrict;
use common\models\ShareLog;

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
     * 给会员添加奖金
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
}
