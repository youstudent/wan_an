<?php

namespace backend\models;

use Yii;


/**
 * This is the model class for table "{{%goods}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $price
 * @property string $describe
 */
class Goods extends \yii\db\ActiveRecord
{
    //暂存图片的id
    public $img_ids;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price'], 'number'],
            [['describe'], 'string'],
            [['name'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名字',
            'price' => '商品价格',
            'describe' => '商品描述',
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

    public function addGoods($post)
    {
        if(!$this->load($post)){
            return null;
        }
        if(!$this->validate()){
            return null;
        }

        if(!isset($post['GoodsImg']['img_path']) || empty($post['GoodsImg']['img_path'])){
            $this->addError('img_ids', '请先上传商品图片');
            return null;
        }
        if(!$this->save()){
            $this->addError('name', '保存失败');
        }
        //更新上传文件
        GoodsImg::bindGoods($this->id, $post['GoodsImg']['img_path']);
        return $this->id ? $this : null;
    }

    public function getGoodsImg()
    {
        return $this->hasMany(GoodsImg::className(), ['goods_id' => 'id']);
    }

    public function getGoodsImgs($goods_id)
    {
        $imgs = GoodsImg::find()->where(['goods_id'=>$goods_id])->select('img_path')->orderBy('id')->column();

        foreach($imgs as &$img){
            $img = Yii::$app->params['img_domain'] . $img;
        }
        return $imgs;
    }
}
