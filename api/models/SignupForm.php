<?php
namespace api\models;

use yii\base\Model;
use app\models\Member;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['site', 'parent_id', 'group_num', 'child_num', 'a_coin', 'b_coin', 'gross_income', 'gorss_bonus', 'last_login_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'password', 'mobile', 'deposit_bank', 'bank_account', 'address'], 'string', 'max' => 255],
            ['status', 'in', 'range' => [0,1]],

            ['password', 'required'],
            ['password', 'string', 'min' => 6, 'max' => 16],
        ];
    }

    /**
     * Signs member up.
     *
     * @return User|null the saved model or null if saving fails
     */

}
