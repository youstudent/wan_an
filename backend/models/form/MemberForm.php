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
     * member form.
     *
     * @return Member|null the saved model or null if saving fails
     */
    public function updateMember($id,$data)
    {
        if ($this->validate()) {
            $member = Member::findOne($id);
            $this->password = $data['Member']['password'];
            $member->setPassword($this->password);
            if ($member->save()) {
                return $member;
            }
        }

        return null;
    }
}
