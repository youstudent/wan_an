<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/1
 * Time: 18:55
 */

namespace api\models;


class FruiterImg extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%fruiter_img}}';
    }
}