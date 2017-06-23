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
            [['password'], 'string', 'min'=>6,'max' => 12,'on'=>'saveUpdate'],
            [['username'], 'string', 'min'=>5,'max' => 5],
            ['mobile','match','pattern'=>'/^0?(13|14|15|18)[0-9]{9}$/','message'=>'手机号码不正确'],
            [['username'], 'string'],
            [['username'], 'match','pattern'=>'/\b[a-zA-Z0-9]{5}\b/','message'=>'{attribute}格式不正确'],
            [['username'], 'unique', 'message' => "{attribute}已经被占用了"],
        ];
    }

    /**
     * member form.
     *
     * @return Member|null the saved model or null if saving fails
     */
    public function updateMember($id, $data)
    {
        if(empty($data['MemberForm']['password'])){
            $this->scenario = 'default';
            if ($this->validate()) {
                $this->password = MemberForm::findOne($id)->password;
                $this->save(false);
                return $this;
            }
            return null;
        } else {
            $this->password = $data['MemberForm']['password'];
            $this->scenario = 'saveUpdate';
            if ($this->validate()) {
                $this->scenario = 'default';
                $this->setPassword($this->password);
                $this->save(false);
                return $this;
            }
            return null;
        }
    }
}
