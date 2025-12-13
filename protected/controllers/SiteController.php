<?php
class SiteController extends Controller
{
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            'error' => array(
                'class' => 'CErrorAction',
            ),
        );
    }

    /**
     * This is the default 'index' action.
     * Redirects to login if not authenticated, dashboard if logged in
     */
    public function actionIndex()
    {
        if (Yii::app()->user->isGuest) {
            $this->redirect(array('site/login'));
        } else {
            $this->redirect(array('site/dashboard'));
        }
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        // If user is already logged in, redirect to dashboard
        if (!Yii::app()->user->isGuest) {
            $this->redirect(array('site/dashboard'));
        }

        $model = new LoginForm();

        // Handle AJAX/API login requests
        if (Yii::app()->request->isAjaxRequest || isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $this->handleAjaxLogin($model);
            return;
        }

        // Collect user input data from web form
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to dashboard if valid
            if ($model->validate() && $model->login()) {
                $this->redirect(array('site/dashboard'));
            }
        }
        
        // Display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Signup page for new users
     */
    public function actionSignup()
{
    // If user is already logged in, redirect to dashboard
    if (!Yii::app()->user->isGuest) {
        $this->redirect(array('site/dashboard'));
    }

    $model = new SignupForm();

    // Handle AJAX/API signup requests
    if (Yii::app()->request->isAjaxRequest || isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        $this->handleAjaxSignup($model);
        return;
    }

    // Collect user input data from web form
    if (isset($_POST['SignupForm'])) {
        $model->attributes = $_POST['SignupForm'];
        
        // DEBUG: Check what data is being submitted
        Yii::log('Signup form submitted: ' . print_r($_POST['SignupForm'], true), 'info');
        
        // Validate and register user
        if ($model->validate()) {
            if ($model->register()) {
                // Auto login after successful registration
                if ($model->autoLogin()) {
                    // Redirect to dashboard
                    $this->redirect(array('site/dashboard'));
                } else {
                    // If auto login fails, still show success message
                    Yii::app()->user->setFlash('success', 'Registration successful! Please login.');
                    $this->redirect(array('site/login'));
                }
            }
        } else {
            // DEBUG: Show validation errors
            Yii::log('Signup validation errors: ' . print_r($model->getErrors(), true), 'error');
        }
    }
    
    // Display the signup form
    $this->render('signup', array('model' => $model));
}
    /**
     * Simple Dashboard page
     */
    public function actionDashboard()
    {
        // Check if user is logged in
        if (Yii::app()->user->isGuest) {
            $this->redirect(array('site/login'));
        }
        
        // Get current user
        $user = User::model()->findByPk(Yii::app()->user->id);
        
        // Simple dashboard data
        $dashboardData = array(
            'welcomeMessage' => 'Welcome to your Dashboard',
            'user' => $user,
            'loginTime' => date('Y-m-d H:i:s'),
            'totalUsers' => User::model()->count(),
        );
        
        $this->render('dashboard', $dashboardData);
    }

    /**
     * Handle AJAX login requests (for API)
     */
    private function handleAjaxLogin($model)
    {
        header('Content-Type: application/json');
        
        // Check if it's a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(array(
                'success' => false,
                'message' => 'No login data provided. Please use POST method.'
            ));
            Yii::app()->end();
        }

        // Get data from POST or raw JSON input
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (!$data) {
            $data = $_POST;
        }

        // Check if data is provided
        if (empty($data['email']) || empty($data['password'])) {
            echo json_encode(array(
                'success' => false,
                'message' => 'No login data provided. Please provide email and password.'
            ));
            Yii::app()->end();
        }

        $model->email = $data['email'];
        $model->password = $data['password'];
        
        if ($model->validate() && $model->login()) {
            $token = $model->generateJwtToken();
            $user = $model->getUser();
            
            echo json_encode(array(
                'success' => true,
                'message' => 'Login successful',
                'data' => array(
                    'token' => $token,
                    'user' => $user->getApiData()
                )
            ));
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => 'Invalid email or password'
            ));
        }
        
        Yii::app()->end();
    }

    /**
     * Handle AJAX signup requests (for API)
     */
    private function handleAjaxSignup($model)
    {
        header('Content-Type: application/json');
        
        // Check if it's a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(array(
                'success' => false,
                'message' => 'Method not allowed. Use POST method.'
            ));
            Yii::app()->end();
        }

        // Get data from POST or raw JSON input
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (!$data) {
            $data = $_POST;
        }

        // Check if data is provided
        if (empty($data['email']) || empty($data['password']) || empty($data['name'])) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Missing required fields: name, email, password.'
            ));
            Yii::app()->end();
        }

        // Assign data to model
        $model->name = $data['name'];
        $model->email = $data['email'];
        $model->password = $data['password'];
        $model->password_repeat = isset($data['password_repeat']) ? $data['password_repeat'] : $data['password'];
        $model->phone = isset($data['phone']) ? $data['phone'] : null;
        $model->role = isset($data['role']) ? $data['role'] : 'user';
        $model->agree_terms = isset($data['agree_terms']) ? $data['agree_terms'] : true;
        
        if ($model->validate() && $model->register()) {
            $token = $model->generateJwtToken();
            $user = $model->getUser();
            
            echo json_encode(array(
                'success' => true,
                'message' => 'Registration successful',
                'data' => array(
                    'token' => $token,
                    'user' => $user->getApiData()
                )
            ));
        } else {
            $errors = $model->getErrors();
            $errorMessages = array();
            foreach ($errors as $field => $messages) {
                $errorMessages[] = implode(', ', $messages);
            }
            
            echo json_encode(array(
                'success' => false,
                'message' => 'Registration failed',
                'errors' => $errorMessages
            ));
        }
        
        Yii::app()->end();
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }
    
    /**
     * Simple Profile page
     */
    public function actionProfile()
    {
        // Check if user is logged in
        if (Yii::app()->user->isGuest) {
            $this->redirect(array('site/login'));
        }
        
        $user = User::model()->findByPk(Yii::app()->user->id);
        
        if (!$user) {
            throw new CHttpException(404, 'User not found.');
        }
        
        $this->render('profile', array(
            'user' => $user,
        ));
    }
}