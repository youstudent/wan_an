<?php

namespace common\components;


use common\models\Bonus;

class Helper{
    /**
     * 生成会员奖金记录
     * @param $member_id
     * @param $coin_type    1:金果  2:金种子
     * @param $type   1:绩效 2:分享 3:额外分享 4:提现 5:注册奖金 6:充值 7:扣除 8:赠送 9:提现返回 10:注册扣除
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
        $model->load($data, '');

        return $model->load($data, '') && $model->save(false) ? $model : null;
    }
}
