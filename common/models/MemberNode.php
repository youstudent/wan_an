<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%member_node}}".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $above_member_id
 */
class MemberNode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_node}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'above_member_id'], 'required'],
            [['member_id', 'above_member_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'above_member_id' => 'Above Member ID',
        ];
    }

    /**
     * 交换两个人挂靠关系
     * @param $member_id_1
     * @param $member_id_2
     * @return bool
     */
    public static function exchangeMemberNode($member_id_1, $member_id_2)
    {
        try{
            Yii::$app->db->createCommand()->update(MemberNode::tableName(), ['member_id'=>-10001], ['member_id'=>$member_id_1])->execute();
            Yii::$app->db->createCommand()->update(MemberNode::tableName(), ['above_member_id'=>-20001], ['above_member_id'=>$member_id_1])->execute();

            Yii::$app->db->createCommand()->update(MemberNode::tableName(), ['member_id'=>-10002], ['member_id'=>$member_id_2])->execute();
            Yii::$app->db->createCommand()->update(MemberNode::tableName(), ['above_member_id'=>-20002], ['above_member_id'=>$member_id_2])->execute();

            Yii::$app->db->createCommand()->update(MemberNode::tableName(), ['member_id'=>$member_id_2], ['member_id'=> '-10001'])->execute();
            Yii::$app->db->createCommand()->update(MemberNode::tableName(), ['above_member_id'=>$member_id_2], ['above_member_id'=> '-20001'])->execute();

            Yii::$app->db->createCommand()->update(MemberNode::tableName(), ['member_id'=>$member_id_1], ['member_id'=> '-10002'])->execute();
            Yii::$app->db->createCommand()->update(MemberNode::tableName(), ['above_member_id'=>$member_id_1], ['above_member_id'=> '-20002'])->execute();
        }catch(\Exception $e){
            return false;
        }
        return true;
    }
}
