<?php

namespace common\models;

use Yii;

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
}
