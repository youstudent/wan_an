<?php
namespace backend\models\form;

use Yii;
use backend\models\Member;
use yii\base\Model;

/**
 * member form
 */
class MemberForm extends Member
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'last_login_time', 'status', 'created_at', 'updated_at',  'a_coin', 'b_coin', 'child_num', 'out_status'], 'integer'],
            [['vip_number'], 'required'],
            [['name', 'mobile', 'deposit_bank', 'bank_account', 'address'], 'string', 'max' => 255],
            [['password'], 'string', 'min'=>6,'max' => 12],
            [['parent_id', 'last_login_time', 'status', 'created_at', 'updated_at', 'vip_number','name',
                'password', 'mobile', 'deposit_bank', 'bank_account', 'address',
                'a_coin', 'b_coin', 'child_num', 'vip_number','out_status'], 'safe'],
        ];
    }

    /**
     * member form.
     *
     * @return Member|null the saved model or null if saving fails
     */
    public function updateMember($id,$data)
    {
        $this->load($data, 'Member');

        $member = $this->findOne($id);
        $member->deposit_bank = $this->deposit_bank;
        $member->mobile = $this->mobile;
        $member->bank_account = $this->bank_account;
        $member->address = $this->address;
        $member->name = $this->name;
        if(!empty($data['Member']['password'])){
            $member->password = $data['Member']['password'];
            $member->setPassword($this->password);
        }

        if ($member->save(false)) {
            return $member;
        }

        return null;
    }
}
