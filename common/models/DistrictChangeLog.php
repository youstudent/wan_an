<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%district_change_log}}".
 *
 * @property integer $id
 * @property integer $old_member_id
 * @property integer $new_member_id
 * @property integer $created_at
 */
class DistrictChangeLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%district_change_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['old_member_id', 'new_member_id', 'created_at'], 'required'],
            [['old_member_id', 'new_member_id', 'created_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'old_member_id' => '会员id',
            'new_member_id' => '新区',
            'created_at' => '创建时间',
        ];
    }

    /**
     * 添加一个区交换记录
     * @param $old_member_id
     * @param $new_member_id
     * @return DistrictChangeLog|null
     */
    public static function addLog($old_member_id, $new_member_id)
    {
        $model = new DistrictChangeLog();
        $model->old_member_id = $old_member_id;
        $model->new_member_id = $new_member_id;
        $model->created_at = time();
        return $model->save() ? $model : null;
    }
}
