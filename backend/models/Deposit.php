<?php

namespace backend\models;

use Yii;
use backend\models\Member;
use common\models\components\Helper;

/**
 * This is the model class for table "wa_deposit".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $type
 * @property integer $operation
 * @property integer $num
 * @property integer $created_at
 * @property integer $updated_at
 */
class Deposit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%deposit}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'type', 'operation', 'num', 'created_at', 'updated_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '充值记录自增ID',
            'member_id' => '会员ID',
            'type' => '币种',
            'operation' => '',
            'num' => '金额',
            'created_at' => '创建时间 奖金获得时间',
            'updated_at' => '更新时间',
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

    //充值和扣除
    public function deposit($data)
    {
        $id = $data['Deposit']['member_id'];
        $result = Member::findOne(['id' => $id]);
        if (!$result) {
            Yii::$app->session->setFlash('error', '用户不存在');
        }
        if ($data['Deposit']['operation'] == 2 ) {
            if ($result->a_coin < $data['Deposit']['num'] || $result->b_coin < $data['Deposit']['num']) {

                Yii::$app->session->setFlash('error', '余额不足,扣除失败');
                return null;
            }
        }

        if ($this->validate()) {
            $this->created_at=time();
            if ($this->save()) {
                $type = $data['Deposit']['operation'] ==1 ? 6 : 7;
                if ($data['Deposit']['operation'] ==1) {
                    $data['Deposit']['type']==1?$result->a_coin += $data['Deposit']['num']:$result->b_coin += $data['Deposit']['num'];
                    $result->save(false);
                } else {
                    $data['Deposit']['type']==1?$result->a_coin -= $data['Deposit']['num']:$result->b_coin -= $data['Deposit']['num'];
                    $result->save(false);
                }

                $Helper= new Helper();
                if ($Helper->pool($id,$data['Deposit']['type'],$type,$data['Deposit']['num'],null,null)===false){
                    return false;
                }
                return $this;
            }
        }

        return null;
    }
}
