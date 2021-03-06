<?php

namespace api\models;

use api\tests\functional\SignupCest;
use SebastianBergmann\CodeCoverage\Report\Xml\Facade;
use Yii;
use app\models\User;


/**
 * This is the model class for table "{{%announcements}}".
 *
 * @property string $id
 * @property string $title
 * @property string $content
 * @property integer $created_at
 */
class Announcements extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%announcements}}';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'img', 'content'], 'required'],
            [['content'], 'string'],
            [['created_at'], 'integer'],
            [['title'], 'string', 'max' => 30],
            [['status'], 'safe']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'content' => '内容',
            'status' => '状态',
            'created_at' => '发布时间',
        ];
    }
    
    
    //公告 接口列表
    public function index()
    {
        $model = self::find()->where(['status'=>1])->select(['id', 'title', 'created_at', 'author'])->orderBy('id DESC')->limit(5)->all();
        if ($model === false || $model==null) {
            //$this->addError('code',0);
            $this->addError('message', '暂时还未发布公告');
            return false;
        }
        foreach ($model as &$v){
            $v['created_at']=date('Y/m/d',$v['created_at']);
        }
        return $model ;
        
    }
    
    //公告 详情
    public function select($id)
    {
        $model = self::findOne(['id' => $id]);
        if ($model === false || $model==null) {
            return false;
        }
        $model['created_at']=date('Y-m-d',$model['created_at']);
        return $model;
    }
    
}