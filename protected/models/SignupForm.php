<?php

class SignupForm extends CFormModel
{
    public $name;
    public $email;
    public $password;
    public $password_repeat;
    public $phone;
    public $role = 'user';
    public $agree_terms;

    public function rules()
    {
        return [
            ['name, email, password, password_repeat', 'required'],
            ['phone, role, agree_terms', 'safe'],
            ['email', 'email', 'message' => 'Enter a valid email address.'],
            ['name', 'length', 'min'=>2, 'max'=>100],
            ['password', 'length', 'min'=>8],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>'Passwords do not match.'],
            ['email', 'unique', 'className'=>'User', 'attributeName'=>'email', 'message'=>'Email already exists.'],
            ['agree_terms', 'boolean'],
            ['agree_terms', 'compare', 'compareValue'=>true, 'message'=>'You must agree to terms.'],
            ['phone', 'match', 'pattern'=>'/^[0-9\-\+\s\(\)]{10,}$/', 'message'=>'Enter a valid phone number.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'Full Name',
            'email'=>'Email Address',
            'password'=>'Password',
            'password_repeat'=>'Confirm Password',
            'phone'=>'Phone Number',
            'role'=>'Account Type',
            'agree_terms'=>'I agree to the terms and conditions',
        ];
    }

    public function register()
    {
        if($this->validate()){
            $user = new User();
            $user->name = $this->name;
            $user->email = $this->email;
            $user->phone = $this->phone;
            $user->role = $this->role;
            $user->userId = $this->generateUserId();
            $user->password = $this->hashPassword($this->password);

            if($user->save()){
                $this->autoLogin($user);
                return true;
            } else {
                $this->addErrors($user->getErrors());
                return false;
            }
        }
        return false;
    }

    private function generateUserId()
    {
        return 'USR-' . time() . '-' . rand(1000, 9999);
    }

    private function hashPassword($password)
    {
        if(function_exists('password_hash')){
            return password_hash($password, PASSWORD_BCRYPT);
        }
        return sha1($password . Yii::app()->params['salt']);
    }

    private function autoLogin($user)
    {
        $identity = new UserIdentity($user->email, $this->password);
        $identity->userId = $user->userId;
        $identity->name = $user->name;
        $identity->errorCode = UserIdentity::ERROR_NONE;
        Yii::app()->user->login($identity, 3600*24*30);

        Yii::app()->user->setState('name',$user->name);
        Yii::app()->user->setState('email',$user->email);
        Yii::app()->user->setState('role',$user->role);
        Yii::app()->user->setState('userId',$user->userId);
    }

    public function getRoleOptions()
    {
        return [
            'user'=>'Regular User',
            'admin'=>'Administrator',
            'editor'=>'Editor',
        ];
    }
}
