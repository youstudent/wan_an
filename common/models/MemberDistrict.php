<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%member_district}}".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $district
 * @property integer $is_extra
 */
class MemberDistrict extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_district}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'district', 'is_extra'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '会员id',
            'district' => '区id',
            'is_extra' => '是否是本身39个会员形成的区；1是 0否',
        ];
    }
}
