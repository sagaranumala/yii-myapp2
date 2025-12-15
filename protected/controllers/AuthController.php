<?php
class AuthController extends Controller
{
    // Disable CSRF for API endpoints only
    public $enableCsrfValidation = false;

    /**
     * Before action - set CORS headers for API, handle web login differently
     */
    public function beforeAction($action)
    {
        // Only set CORS headers for API actions (login, signup endpoints)
        if (in_array($action->id, array('login', 'signup')) && 
            (Yii::app()->request->isAjaxRequest || isset($_SERVER['HTTP_X_REQUESTED_WITH']))) {
            $this->setCorsHeaders();
        }
        return parent::beforeAction($action);
    }

    /**
     * Set CORS headers for API
     */
    private function setCorsHeaders()
    {
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header("Access-Control-Allow-Credentials: true");
            header("Access-Control-Max-Age: 86400");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type, Authorization");
            Yii::app()->end();
        }
    }

    /**
     * WEB: GET /auth/login - Display login form
     * API: POST /auth/login - Handle API login
     */
    public function actionLogin()
    {
        $model = new LoginForm();

        // Handle API/JSON login requests
        if (Yii::app()->request->isAjaxRequest || isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $this->handleApiLogin($model);
            return;
        }

        // Handle WEB form login
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            if ($model->validate() && $model->login()) {
                $this->redirect(array('site/dashboard'));
            }
        }

        // Display the web login form
        $this->render('login', array('model' => $model));
    }

    /**
     * WEB: GET /auth/signup - Display signup form
     * API: POST /auth/signup - Handle API signup
     */
    public function actionSignup()
    {
        $model = new SignupForm();

        // Handle API/JSON signup requests
        if (Yii::app()->request->isAjaxRequest || isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $this->handleApiSignup($model);
            return;
        }

        // Handle WEB form signup
        if (isset($_POST['SignupForm'])) {
            $model->attributes = $_POST['SignupForm'];
            if ($model->validate() && $model->register()) {
                if ($model->autoLogin()) {
                    $this->redirect(array('site/dashboard'));
                } else {
                    Yii::app()->user->setFlash('success', 'Registration successful! Please login.');
                    $this->redirect(array('auth/login'));
                }
            }
        }

        // Display the web signup form
        $this->render('signup', array('model' => $model));
    }

    /**
     * Handle API login
     */
    private function handleApiLogin($model)
    {
        $this->requirePost();

        $data = $this->getJsonInput();

        if (empty($data['email']) || empty($data['password'])) {
            $this->sendJson(false, 'Email and password required');
        }

        $model->email = $data['email'];
        $model->password = $data['password'];

        if ($model->validate() && $model->login()) {
            $this->sendJson(true, 'Login successful', [
                'token' => $model->generateJwtToken(),
                'user'  => $model->getUser()->getApiData(),
            ]);
        }

        $this->sendJson(false, 'Invalid credentials');
    }

    /**
     * Handle API signup
     */
    private function handleApiSignup($model)
    {
        $this->requirePost();
        $data = $this->getJsonInput();

        $model->attributes = $data;

        if ($model->validate() && $model->register()) {
            $this->sendJson(true, 'Registration successful', [
                'token' => $model->generateJwtToken(),
                'user'  => $model->getUser()->getApiData(),
            ]);
        }

        $this->sendJson(false, 'Registration failed', $model->getErrors());
    }

    /**
     * API Helpers
     */
    private function getJsonInput()
    {
        $raw = file_get_contents('php://input');
        return json_decode($raw, true) ?: $_POST;
    }

    private function requirePost()
    {
        if (!Yii::app()->request->isPostRequest) {
            $this->sendJson(false, 'POST required', null, 405);
        }
    }

    private function sendJson($success, $message, $data = null, $code = 200)
    {
        http_response_code($code);
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data'    => $data
        ]);
        Yii::app()->end();
    }
}