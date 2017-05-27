<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%goods_img}}".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property string $img_path
 */
class GoodsImg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_img}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id'], 'integer'],
            [['img_path'], 'required'],
            [['img_path'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => 'Goods ID',
            'img_path' => 'Img Path',
        ];
    }
}
