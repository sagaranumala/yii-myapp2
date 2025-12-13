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
        if (in_array($action->id, array('login', 'register', 'validate', 'logout'))) {
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
     * Register new user
     * POST /auth/register
     */
    public function actionRegister()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJsonResponse(false, 'Method not allowed', null, 405);
        }

        // Get data
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (!$data) {
            $data = $_POST;
        }

        if (empty($data['email']) || empty($data['password']) || empty($data['name'])) {
            $this->sendJsonResponse(false, 'Missing required fields: email, password, name');
        }

        // Check existing user
        $existing = User::model()->findByAttributes(array('email' => $data['email']));
        if ($existing) {
            $this->sendJsonResponse(false, 'Email already registered');
        }

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $user->hashPassword($data['password']);
        $user->phone = isset($data['phone']) ? $data['phone'] : null;
        $user->role = isset($data['role']) ? $data['role'] : 'user';
        $user->userId = 'usr_' . uniqid();

        if ($user->save()) {
            $token = Yii::app()->jwt->generateToken(
                $user->id,
                $user->email,
                $user->role,
                $user->name
            );
            
            $this->sendJsonResponse(true, 'Registration successful', array(
                'token' => $token,
                'user' => $user->getApiData()
            ));
        } else {
            $this->sendJsonResponse(false, 'Registration failed', $user->getErrors());
        }
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