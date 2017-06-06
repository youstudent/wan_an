<?php

namespace common\models;

use Symfony\Component\Debug\Tests\Fixtures\DeprecatedInterface;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%district}}".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $district
 * @property integer $seat
 * @property integer $created_at
 */
class District extends \yii\db\ActiveRecord
{
    public $errorMsg = '';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%district}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'district'], 'required'],
            [['member_id', 'district', 'seat', 'created_at'], 'integer'],
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
            'district' => 'District',
            'seat' => 'Seat',
            'created_at' => 'Created At',
        ];
    }

    /**
     * 获取一个简单的树状结构
     * @param $post
     * @return bool|mixed
     */
    public function simpleTree($post, $default_vip_number = null)
    {
        $vip_number = ArrayHelper::getValue($post, 'vip_number', null);
        $up =  ArrayHelper::getValue($post, 'up', 0);
        if(empty($vip_number)){
            if(is_null($default_vip_number)){
                $this->errorMsg = '未获取到登陆信息';
                return false;
            }
            $vip_number = $default_vip_number;
        }

        //区分是向上还是向下
        if($up){
            $root_district = $this->getMemberParentDistrict($vip_number);
        }else{
            $root_district = $this->getMemberDistrict($vip_number);
        }
        if(!isset($root_district)){
            $this->errorMsg = '获取不到会员信息';
            return false;
        }
        //查询这个区域下所有的会员
        $data = $this->getDistrictMember($root_district['district'], 'm.vip_number,d.seat');
        $output = $data[0];
        $i = 1;
        while(isset($data[$i])){
            $output['num'] = $i;
            $output['child'][] = $data[$i];
            $i++;
        }
        return $output;
    }

    /**
     * 查询单个会员的信息
     * @param $vip_number
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getMemberDistrict($vip_number)
    {
        return (new \yii\db\Query())->from(self::tableName() . ' d')->where(['vip_number'=>$vip_number, 'seat'=>1])->innerJoin('{{%member}} m', 'm.id = d.member_id')->select('m.vip_number,d.*')->one();
    }

    /**
     * 找到指定区域指定的座位数据
     * @param $district
     * @param string $select
     * @param array $seat
     * @return array
     */
    public function getDistrictMember($district, $select = 'm.vip_number,d.*', $seat = [1,2,3,4])
    {
        return (new \yii\db\Query())->from(self::tableName() . ' d')->where(['seat'=>$seat, 'district'=>$district])->innerJoin('{{%member}} m', 'm.id = d.member_id')->select($select)->orderBy('seat')->all();
    }

    /**
     *  找到最近的一个节点，即上翻的节点
     * @param $vip_number
     * @return array|bool
     */
    public function getMemberParentDistrict($vip_number)
    {
        return (new \yii\db\Query())->from(self::tableName() . ' d')->where('vip_number = :vip_number and seat >1 ', [':vip_number' => $vip_number])->innerJoin('{{%member}} m', 'm.id = d.member_id')->select('m.vip_number,d.*')->orderBy(['seat'=>SORT_ASC])->one();
    }


}
