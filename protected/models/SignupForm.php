<?php

/**
 * SignupForm class.
 * SignupForm is the data structure for keeping user registration form data.
 * Updated for Node.js users table structure.
 */
class SignupForm extends CFormModel
{
    // Form fields matching your users table
    public $name;
    public $email;
    public $password;
    public $password_repeat;
    public $phone;
    public $role = 'user'; // Default role
    public $agree_terms;
    
    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            // Required fields (based on your User model rules)
            array('name, email, password, password_repeat', 'required'),
            
            // Phone is optional but safe
            array('phone, role, agree_terms', 'safe'),
            
            // Email validation
            array('email', 'email', 'message' => 'Please enter a valid email address.'),
            
            // String length validations
            array('name', 'length', 'min' => 2, 'max' => 100,
                  'tooShort' => 'Name must be at least 2 characters.',
                  'tooLong' => 'Name cannot exceed 100 characters.'),
            
            array('password', 'length', 'min' => 8,
                  'tooShort' => 'Password must be at least 8 characters.'),
            
            // Match password and confirmation
            // array('password_repeat', 'compare', 'compareAttribute' => 'password',
            //       'message' => 'Passwords do not match.'),
            
            // Check if email already exists in users table
            array('email', 'unique', 'className' => 'User', 'attributeName' => 'email',
                  'message' => 'This email is already registered.'),
            
            // Boolean validation for checkbox
            array('agree_terms', 'boolean'),
            array('agree_terms', 'compare', 'compareValue' => true,
                  'message' => 'You must agree to the terms and conditions.'),
            
            // Phone format validation (optional)
            array('phone', 'match', 'pattern' => '/^[0-9\-\+\s\(\)]{10,}$/',
                  'message' => 'Please enter a valid phone number.'),
        );
    }
    
    /**
     * Declares customized attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'name' => 'Full Name',
            'email' => 'Email Address',
            'password' => 'Password',
            'password_repeat' => 'Confirm Password',
            'phone' => 'Phone Number',
            'role' => 'Account Type',
            'agree_terms' => 'I agree to the terms and conditions',
        );
    }
    
    /**
     * Custom validation for password strength
     */
    public function validatePasswordStrength($attribute, $params)
    {
        if (!empty($this->$attribute)) {
            $password = $this->$attribute;
            
            // Check for at least one uppercase letter
            if (!preg_match('/[A-Z]/', $password)) {
                $this->addError($attribute, 'Password must contain at least one uppercase letter.');
            }
            
            // Check for at least one lowercase letter
            if (!preg_match('/[a-z]/', $password)) {
                $this->addError($attribute, 'Password must contain at least one lowercase letter.');
            }
            
            // Check for at least one number
            if (!preg_match('/[0-9]/', $password)) {
                $this->addError($attribute, 'Password must contain at least one number.');
            }
        }
    }
    
    /**
     * Registers a new user in the Node.js users table
     * @return boolean whether registration is successful
     */
    public function register()
    {
        if ($this->validate()) {
            // Create new user record
            $user = new User();
            $user->name = $this->name;
            $user->email = $this->email;
            $user->phone = $this->phone;
            $user->role = $this->role;
            
            // Generate unique userId (as your table requires)
            $user->userId = $this->generateUserId();
            
            // Hash the password before saving
            $user->password = $this->hashPassword($this->password);
            
            // Save to database
            if ($user->save()) {
                // Automatically log the user in after registration
                $this->autoLogin($user);
                return true;
            } else {
                // Copy errors from User model to SignupForm
                $this->addErrors($user->getErrors());
                return false;
            }
        }
        return false;
    }
    
    /**
     * Generate unique userId for Node.js users table
     * @return string unique user ID
     */
    private function generateUserId()
    {
        // Generate a unique ID (you can adjust this based on your Node.js format)
        // Example: USR-2023-00123 or using UUID
        return 'USR-' . time() . '-' . rand(1000, 9999);
    }
    
    /**
     * Hash password before storing
     * Note: Node.js might use different hashing. This should match your Node.js auth.
     * @param string $password
     * @return string hashed password
     */
    private function hashPassword($password)
    {
        // IMPORTANT: This must match your Node.js password hashing method
        // If Node.js uses bcrypt:
        if (function_exists('password_hash')) {
            return password_hash($password, PASSWORD_BCRYPT);
        }
        // If Node.js uses a different method, adjust accordingly
        // Example if using md5 (not recommended for production):
        // return md5($password);
        
        // Default fallback
        return sha1($password . Yii::app()->params['salt']);
    }
    
    /**
     * Automatically log user in after registration
     * @param User $user The user model
     */
    private function autoLogin($user)
    {
        // Create user identity for login
        $identity = new UserIdentity($user->email, $this->password);
        $identity->id = $user->userId; // Use userId as identity ID
        $identity->name = $user->name;
        
        // Set authentication state
        $identity->errorCode = UserIdentity::ERROR_NONE;
        
        // Log the user in
        Yii::app()->user->login($identity, 3600*24*30); // 30 days remember
        
        // Store additional user data in session
        Yii::app()->user->setState('name', $user->name);
        Yii::app()->user->setState('email', $user->email);
        Yii::app()->user->setState('role', $user->role);
        Yii::app()->user->setState('userId', $user->userId);
    }
    
    /**
     * Get role options for dropdown
     * @return array role options
     */
    public function getRoleOptions()
    {
        return array(
            'user' => 'Regular User',
            'admin' => 'Administrator',
            'editor' => 'Editor',
            // Add other roles as needed
        );
    }
}