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
        ];
    }

    /**
     * member form.
     *
     * @return Member|null the saved model or null if saving fails
     */
    public function updateMember($id,$data)
    {
        $member = Member::findOne($id);

        $member->load($data, 'Member');

        $this->vip_number = $data['Member']['vip_number'];
        $this->password = $data['Member']['password'];
        $member->setPassword($this->password);

        if ($this->validate() && $member->save()) {
            return $member;
        }

        return null;
    }
}
