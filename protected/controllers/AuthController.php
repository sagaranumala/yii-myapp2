<?php
class AuthController extends Controller
{
    /**
     * Before action - set CORS headers
     */
    public function beforeAction($action)
    {
        // Set CORS headers for API endpoints
        $this->setCorsHeaders();
        
        // Skip CSRF for API endpoints
        if (in_array($action->id, array('login', 'signup', 'validate', 'logout'))) {
            $this->enableCsrfValidation = false;
        }
        
        return parent::beforeAction($action);
    }

    /**
     * Set CORS headers
     */
    private function setCorsHeaders()
    {
        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
            
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            
            exit(0);
        }
    }

    /**
     * API Login endpoint
     * POST /auth/login
     */
    public function actionLogin()
    {
        // Set JSON content type
        header('Content-Type: application/json');
        
        // Check if it's a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJsonResponse(false, 'Method not allowed. Use POST.', null, 405);
        }

        // Get data from POST or raw JSON
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        // Fallback to regular POST
        if (!$data) {
            $data = $_POST;
        }

        // Check if data provided
        if (empty($data['email']) || empty($data['password'])) {
            $this->sendJsonResponse(false, 'No login data provided. Provide email and password.');
        }

        // Create login form
        $model = new LoginForm();
        $model->email = $data['email'];
        $model->password = $data['password'];
        
        // Validate and login
        if ($model->validate() && $model->login()) {
            $token = $model->generateJwtToken();
            $user = $model->getUser();
            
            if ($token) {
                $this->sendJsonResponse(true, 'Login successful', array(
                    'token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => Yii::app()->jwt->expireTime,
                    'user' => $user->getApiData()
                ));
            } else {
                $this->sendJsonResponse(false, 'Failed to generate token');
            }
        } else {
            $this->sendJsonResponse(false, 'Invalid email or password');
        }
    }

    /**
     * API Signup endpoint
     * POST /auth/signup
     */
    public function actionSignup()
    {
        // Set JSON content type
        header('Content-Type: application/json');
        
        // Check if it's a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJsonResponse(false, 'Method not allowed. Use POST.', null, 405);
        }

        // Get data from POST or raw JSON
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        // Fallback to regular POST
        if (!$data) {
            $data = $_POST;
        }

        // Check if data provided
        if (empty($data['email']) || empty($data['password']) || empty($data['name'])) {
            $this->sendJsonResponse(false, 'Missing required fields: name, email, password.');
        }

        // Create signup form
        $model = new SignupForm();
        $model->name = $data['name'];
        $model->email = $data['email'];
        $model->password = $data['password'];
        $model->password_repeat = isset($data['password_repeat']) ? $data['password_repeat'] : $data['password'];
        $model->phone = isset($data['phone']) ? $data['phone'] : null;
        $model->role = isset($data['role']) ? $data['role'] : 'user';
        $model->agree_terms = isset($data['agree_terms']) ? $data['agree_terms'] : true;
        
        // Validate and register
        if ($model->validate() && $model->register()) {
            $token = $model->generateJwtToken();
            $user = $model->getUser();
            
            if ($token) {
                $this->sendJsonResponse(true, 'Registration successful', array(
                    'token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => Yii::app()->jwt->expireTime,
                    'user' => $user->getApiData()
                ));
            } else {
                $this->sendJsonResponse(false, 'Failed to generate token');
            }
        } else {
            $errors = $model->getErrors();
            $errorMessages = array();
            foreach ($errors as $field => $messages) {
                $errorMessages = array_merge($errorMessages, $messages);
            }
            
            $this->sendJsonResponse(false, 'Registration failed', array(
                'errors' => $errorMessages
            ));
        }
    }

    /**
     * Validate JWT token
     * GET /auth/validate
     */
    public function actionValidate()
    {
        header('Content-Type: application/json');
        
        $userData = Yii::app()->jwt->getCurrentUser();
        
        if ($userData) {
            $this->sendJsonResponse(true, 'Token is valid', $userData);
        } else {
            $this->sendJsonResponse(false, 'Invalid or expired token', null, 401);
        }
    }

    /**
     * Register new user (alias for signup - kept for backward compatibility)
     * POST /auth/register
     */
    public function actionRegister()
    {
        // Just call the signup action
        $this->actionSignup();
    }

    /**
     * Logout
     * POST /auth/logout
     */
    public function actionLogout()
    {
        header('Content-Type: application/json');
        
        Yii::app()->user->logout();
        $this->sendJsonResponse(true, 'Logged out successfully');
    }

    /**
     * Send JSON response
     */
    private function sendJsonResponse($success, $message, $data = null, $httpCode = 200)
    {
        http_response_code($httpCode);
        
        $response = array(
            'success' => $success,
            'message' => $message,
            'data' => $data
        );
        
        echo json_encode($response);
        Yii::app()->end();
    }
}