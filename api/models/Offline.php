<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "wa_offline".
 *
 * @property string $id
 * @property integer $start
 * @property integer $end
 */
class Offline extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%offline}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start', 'end'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'start' => '关网开始时间',
            'end' => '关网结束时间',
        ];
    }
}
