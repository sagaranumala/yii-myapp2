<?php
// controllers/ApiController.php
class ApiController extends Controller
{
    // Disable CSRF for API calls
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
            
            if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
                header('HTTP/1.1 200 OK');
                exit();
            }
            
            return true;
        }
        return false;
    }

    // Get all users
    public function actionGetUsers()
{
    $db = Yii::app()->db;
    $command = $db->createCommand("SELECT * FROM users"); // table `users` in `ticketdb`
    $users = $command->queryAll();

    header('Content-Type: application/json');
    echo json_encode($users);
    Yii::app()->end();
}


    // Get user by ID
    public function actionGetUser($id)
    {
        $user = User::model()->findByPk($id);
        
        if ($user) {
            $response = array(
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'userId' => $user->userId
            );
            $this->sendJsonResponse(200, 'User found', $response);
        } else {
            $this->sendJsonResponse(404, 'User not found');
        }
    }

    // Get user by email (matching your Node.js method)
    public function actionGetUserByEmail($email)
    {
        $user = User::model()->findByAttributes(array('email' => $email));
        
        if ($user) {
            $response = array(
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'userId' => $user->userId
            );
            $this->sendJsonResponse(200, 'User found', $response);
        } else {
            $this->sendJsonResponse(404, 'User not found');
        }
    }

    // Get user by userId (your custom field)
    public function actionGetUserByUserId($userId)
    {
        $user = User::model()->findByAttributes(array('userId' => $userId));
        
        if ($user) {
            $response = array(
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'userId' => $user->userId
            );
            $this->sendJsonResponse(200, 'User found', $response);
        } else {
            $this->sendJsonResponse(404, 'User not found');
        }
    }

    // Create new user (similar to your Node.js create method)
    public function actionCreateUser()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data) {
            $this->sendJsonResponse(400, 'Invalid JSON data');
            return;
        }
        
        $user = new User();
        $user->attributes = $data;
        
        // Hash password if it exists in data
        if (isset($data['password'])) {
            $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        if ($user->save()) {
            $response = array(
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'userId' => $user->userId
            );
            $this->sendJsonResponse(201, 'User created successfully', $response);
        } else {
            $errors = $user->getErrors();
            $this->sendJsonResponse(400, 'Validation failed', $errors);
        }
    }

    // Update user
    public function actionUpdateUser($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $user = User::model()->findByPk($id);
        if (!$user) {
            $this->sendJsonResponse(404, 'User not found');
            return;
        }
        
        $user->attributes = $data;
        
        // Hash password if it's being updated
        if (isset($data['password'])) {
            $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        if ($user->save()) {
            $response = array(
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'userId' => $user->userId
            );
            $this->sendJsonResponse(200, 'User updated successfully', $response);
        } else {
            $errors = $user->getErrors();
            $this->sendJsonResponse(400, 'Validation failed', $errors);
        }
    }

    // Delete user
    public function actionDeleteUser($id)
    {
        $user = User::model()->findByPk($id);
        if ($user && $user->delete()) {
            $this->sendJsonResponse(200, 'User deleted successfully');
        } else {
            $this->sendJsonResponse(404, 'User not found or could not be deleted');
        }
    }

    // Helper method to send JSON responses
    private function sendJsonResponse($statusCode, $message, $data = null)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        
        $response = array(
            'success' => $statusCode >= 200 && $statusCode < 300,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        );
        
        if ($data !== null) {
            $response['data'] = $data;
        }
        
        echo json_encode($response);
        Yii::app()->end();
    }
}