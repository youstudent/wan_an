<?php

namespace backend\models;

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
    public $start_h;
    public $start_i;
    public $end_h;
    public $end_i;
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
            [['start', 'end'], 'safe'],
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

    /**
     * 时间解析
     */
    public function offline($model)
    {
        $start = explode('时',$model->start);
        $end = explode('时',$model->end);
        $model->start_h = $start[0];
        $model->start_i = preg_replace('/\D/s', '', $start[1]);
        $model->end_h = $end[0];
        $model->end_i = preg_replace('/\D/s', '', $end[1]);
        return $model;
    }
    /**
     * 时间修改
     */
    public function updateTime($data,$model)
    {
//        if (is_int($data['Offline']['start_h'])) {
//            $model->start = $data['Offline']['start_h'].'时'.' '.$data['Offline']['start_i'].'分';
//        }
        $model->start = $data['Offline']['start_h'].'时'.$data['Offline']['start_i'].'分';
        $model->end = $data['Offline']['end_h'].'时'.$data['Offline']['end_i'].'分';

        if ($model->save(false)) {
            return true;
        }

        return false;
    }
}
