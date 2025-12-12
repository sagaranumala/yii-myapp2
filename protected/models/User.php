

<?php
class User extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'users';
    }

    public function rules()
    {
        return array(
            array('name, email, password, userId', 'required'),
            array('phone, role', 'safe'),
            array('email', 'email'),
            array('email', 'unique'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'userId' => 'User ID',
            'name' => 'Full Name',
            'email' => 'Email',
            'password' => 'Password',
            'phone' => 'Phone Number',
            'role' => 'Role',
            'createdAt' => 'Created At',
        );
    }
}
