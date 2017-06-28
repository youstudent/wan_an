<?php

namespace backend\models;

use Yii;
use backend\models\Member;


/**
 * This is the model class for table "{{%outline}}".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $username
 * @property string $name
 * @property string $mobile
 * @property integer $vip_number
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $ext_data
 */
class Outline extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%outline}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'vip_number', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'name', 'vip_number'], 'required'],
            [['username'], 'string', 'max' => 6],
            [['name'], 'string', 'max' => 60],
            [['mobile'], 'string', 'max' => 15],
            [['ext_data'], 'string', 'max' => 255]
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
            'username' => '用户名',
            'name' => '姓名',
            'mobile' => '手机号',
            'vip_number' => '会员编号',
            'status' => '状态',
            'created_at' => '退网时间',
            'updated_at' => '操作时间',
            'ext_data' => '扩展数据',
        ];
    }
    
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'member_id']);
    }

    public function change($param)
    {
        $model = Outline::findOne($param['id']);

        $model->status = 1;
        if ($model->save(false)) {
            return $model;
        }

        return  null;
    }
}
