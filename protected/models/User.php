<?php
/**
 * User model
 */
class User extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'users';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('name, email, password, userId', 'required'),
            array('name, email, password, phone, role, userId', 'length', 'max' => 255),
            array('email, userId', 'unique'),
            array('email', 'email'),
            array('role', 'default', 'value' => 'user'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'phone' => 'Phone',
            'role' => 'Role',
            'createdAt' => 'Created At',
            'userId' => 'User ID',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Finds user by email
     */
    public static function findByEmail($email)
    {
        return self::model()->findByAttributes(array('email' => $email));
    }

    /**
     * Finds user by userId
     */
    public static function findByUserId($userId)
    {
        return self::model()->findByAttributes(array('userId' => $userId));
    }

    /**
     * Validates password
     */
    public function validatePassword($password)
    {
        return CPasswordHelper::verifyPassword($password, $this->password);
    }

    /**
     * Generates password hash from password
     */
    public function hashPassword($password)
    {
        return CPasswordHelper::hashPassword($password);
    }

    /**
     * Before save hook
     */
    protected function beforeSave()
    {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                if (empty($this->userId)) {
                    $this->userId = 'usr_' . uniqid();
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Get user data for API response
     */
    public function getApiData()
    {
        return array(
            'id' => $this->id,
            'userId' => $this->userId,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'phone' => $this->phone,
            'createdAt' => $this->createdAt
        );
    }
}