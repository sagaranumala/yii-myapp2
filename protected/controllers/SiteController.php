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
     */
    public function actionIndex()
    {
        $this->render('index');
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        // If user is already logged in, redirect to home
        if (!Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->homeUrl);
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
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                $this->redirect(Yii::app()->user->returnUrl);
            }
        }
        
        // Display the login form
        $this->render('login', array('model' => $model));
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
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }
}