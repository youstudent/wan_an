<?php

namespace backend\models;

use Yii;
use backend\models\Member;


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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '会员ID',
            'member.id' => '会员ID',
            'member.name' => '姓名',
            'order_sn' => '订单号',
            'fruter_img.img_path' =>'果树图片',
            'fruiter_name' => '果树名称',
            'updated_at' => 'Updated At',
            'fruiter_img' => '果树图片',
            'created_at' => '补充时间',
            'status' => '状态',
        ];
    }

    /**
    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->createDate = date('Y-m-d H:i:s');
            $this->userCreate = Yii::$app->user->id;
            $this->userUpdate = Yii::$app->user->id;
        } else {
            $this->updateDate = date('Y-m-d H:i:s');
            $this->userUpdate = Yii::$app->user->id;
        }
        return parent::beforeSave($insert);
    }
    
    public function getUserCreateLabel() {

        $user = User::find()->select('username')->where(['id' => $this->userCreate])->one();
        return $user->username;
    }

    public function getUserUpdateLabel() {
        $user = User::find()->select('username')->where(['id' => $this->userUpdate])->one();
        return $user->username;
    }

    */

    /**
     * @inheritdoc
     */
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'member_id']);
    }
    public function addFruiter($post)
    {
        if(!$this->load($post)){
            return null;
        }
        if(!$this->validate()){
            return null;
        }

        if(!isset($post['FruiterImg']['img_path']) || empty($post['FruiterImg']['img_path'])){
            $this->addError('img_ids', '请先上传果树图片');
            return null;
        }
        if(!$this->save()){
            $this->addError('fruiter_name', '保存失败');
        }
        //更新上传文件
        FruiterImg::bindFruiter($this->id, $post['FruiterImg']['img_path']);
        return $this->id ? $this : null;
    }

    public function updateFruiter($id,$post)
    {

        if(!isset($post['FruiterImg']['img_path']) || empty($post['FruiterImg']['img_path'])){
            Yii::$app->session->setFlash('error', '请先上传果树!  ');
            return null;
        }

        //更新上传文件
        FruiterImg::bindFruiter($id, $post['FruiterImg']['img_path']);
        $fruiter = Fruiter::findOne($id);
        $fruiter->fruiter_name = $post['Fruiter']['fruiter_name'];
        $fruiter->status = 1;
        if ($fruiter->save()){
            return $fruiter;
        }
        return null;

    }
    public function getFruiterImg()
    {
        return $this->hasOne(FruiterImg::className(), ['fruiter_id' => 'id']);
    }

    public function getFruiterImgs($fruiter_id)
    {
        $imgs = FruiterImg::find()->where(['fruiter_id'=>$fruiter_id])->select('img_path')->orderBy('id')->column();

        foreach($imgs as &$img){
            $img = Yii::$app->params['img_domain'] . $img;
        }

        return $imgs;
    }

    public function delFruiter($id)
    {
        $model = Fruiter::findOne($id);
        $FruiterImgModel = FruiterImg::findOne(['fruiter_id'=>$id]);
        $FruiterImgModel->delete();
        $model->status = 0;
        if ($model->save(false)) {
            return true;
        }
        return false;
    }
}
