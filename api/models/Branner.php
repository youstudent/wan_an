<?php

namespace api\models;

use Yii;
use app\models\User;
use api\models\Member;
use api\models\Announcements;


/**
 * This is the model class for table "{{%branner}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $img
 * @property string $content_img
 */
class Branner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%branner}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','content','img'],'required' ],
            [['name'], 'string', 'max' => 30],
            [['status'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '标题',
            'img' => '图片',
            'content' => '文本',
        ];
    }
    
    //广告管理列表
    public function branner()
    {
        $model = self::find()->where(['status'=>1])->select(['id', 'img'])->orderBy('id DESC')->limit(3)->all();
        if ($model === false || $model==null) {
            return false;
        }
        foreach ($model as &$v) {
            $v['img'] = Yii::$app->params['img_domain'].$v['img'];
        }
        $title = Announcements::find()->select(['title'])->orderBy('created_at DESC')->limit(1)->all();

        $session = Yii::$app->session->get('member');
        $member_id = $session['member_id'];

        $query = (new \yii\db\Query());
        $out_status = $query->select('out_status')->from(Member::tableName())->where(['vip_number' => $member_id])->one();

        $model = ['adv'=>$model, 'title'=>$title, 'out_status'=>$out_status];

        return $model;

    }
    
    //广告详情
    public function listid($id)
    {
        $model = self::findOne(['id' => $id]);
        if ($model === false || $model == null) {
            return false;
        }
        $model->img = Yii::$app->params['img_domain'].$model->img;
        return $model;
    }
    
}
