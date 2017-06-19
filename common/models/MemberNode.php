<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%member_node}}".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $above_member_id
 */
class MemberNode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_node}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'above_member_id'], 'required'],
            [['member_id', 'above_member_id'], 'integer'],
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
            'above_member_id' => 'Above Member ID',
        ];
    }
}
