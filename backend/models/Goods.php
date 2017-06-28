<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;


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

    public $errorMsg;
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
            [['price', 'name'], 'required'],
            [['price'], 'integer'],
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
        //同步上传逻辑,处理图片
        $upload = new Upload();
        $img_id = $upload->uploadGoodsImgs(null);
        if(!isset($img_id)){
            $this->errorMsg = '图片获取失败';
            return null;
        }
        if(!$this->save()){
            $this->errorMsg = '保存失败';
            return null;
        }
        //更新上传文件
        GoodsImg::bindGoods($this->id, $img_id);
        return $this->id ? $this : null;
    }
    public function updateGoods($post)
    {
        if(!$this->load($post)){
            return null;
        }

        if(!$this->validate()){
            return null;
        }
        //同步上传逻辑,处理图片 - 没有图片上传的情况
        $GoodsImg = new GoodsImg();
        $handle = UploadedFile::getInstance($GoodsImg, 'img_path');
        if(!isset($handle)){
            if(!$this->save()){
                $this->errorMsg = '保存失败';
                return null;
            }
            return $this;
        }

        $upload = new Upload();
        $img_id = $upload->uploadGoodsImgs(null);
        if(!isset($img_id)){
            $this->errorMsg = '图片获取失败';
            return null;
        }
        //更新上传文件
        GoodsImg::bindGoods($this->id, $img_id, true);

        if(!$this->save()){
            $this->errorMsg = '保存失败';
            return null;
        }
        return $this;


    }

    public function getGoodsImg()
    {
        return $this->hasMany(GoodsImg::className(), ['goods_id' => 'id']);
    }

    public function getGoodsImgs($goods_id)
    {
        $imgs = GoodsImg::find()->where(['goods_id'=>$goods_id])->select('img_path')->limit(1)->orderBy('id')->column();

        foreach($imgs as &$img){
            $img = Yii::$app->params['img_domain'] . $img;
        }
        return $imgs;
    }
}
