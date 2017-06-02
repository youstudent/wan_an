<?php

namespace api\models;
use api\models\FruiterImg;
use Yii;


/**
 * This is the model class for table "wa_fruiter".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $order_sn
 * @property string $fruiter_name
 * @property integer $updated_at
 * @property string $fruiter_img
 * @property integer $created_at
 * @property integer $status
 */
class Fruiter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fruiter}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'member_id', 'order_sn', 'created_at'], 'required'],
            [['id', 'member_id', 'updated_at', 'created_at', 'status'], 'integer'],
            [['order_sn', 'fruiter_img'], 'string', 'max' => 255],
            [['fruiter_name'], 'string', 'max' => 20]
        ];
    }

    public function getFruiterImg()
    {
        return $this->hasOne(FruiterImg::tableName(),['id'=>'fruiter_id'])->one();
    }

    /**
     * @inheritdoc
     */
    /**
     * 获取一个会员的果树
     * @return array
     */
    public function getFruiter()
    {
        $session = Yii::$app->session->get('member');
        $member_id = $session['member_id'];
        // 测试
        $member_id = 2;

        $query = (new \yii\db\Query());
        $fruiter = $query->select('fruiter_name,img_path')->from(Fruiter::tableName())->leftJoin(FruiterImg::tableName(), '{{%fruiter_img}}.fruiter_id = {{%fruiter}}.id')->where(['member_id' => $member_id])->one();
        if($fruiter['img_path']){
            $fruiter['img_path'] = Yii::$app->params['img_domain'].$fruiter['img_path'];
        }
        if(!isset($fruiter) || empty($fruiter)){
            return null;
        }
        return $fruiter;
    }
}
