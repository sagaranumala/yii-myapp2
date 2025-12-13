<?php
/**
 * JWT Helper for Yii 1.x
 */
class JwtHelper extends CApplicationComponent
{
    public $secretKey;
    public $algorithm = 'HS256';
    public $expireTime = 86400; // 24 hours

    public function init()
    {
        parent::init();
        if (empty($this->secretKey)) {
            $this->secretKey = getenv('JWT_SECRET') ?: 'your-super-secret-jwt-key-2024';
        }
        
        // Include Firebase JWT library
        require_once Yii::getPathOfAlias('application.vendor') . '/firebase/php-jwt/src/JWT.php';
        require_once Yii::getPathOfAlias('application.vendor') . '/firebase/php-jwt/src/Key.php';
    }

    /**
     * Generate JWT token
     */
    public function generateToken($userId, $email, $role, $name)
    {
        $issuedAt = time();
        $expire = $issuedAt + $this->expireTime;
        
        $payload = array(
            'iss' => 'yii-jwt-auth',
            'aud' => 'yii-app',
            'iat' => $issuedAt,
            'exp' => $expire,
            'data' => array(
                'userId' => $userId,
                'email' => $email,
                'role' => $role,
                'name' => $name
            )
        );

        return Firebase\JWT\JWT::encode($payload, $this->secretKey, $this->algorithm);
    }

    /**
     * Validate JWT token
     */
    public function validateToken($token)
    {
        try {
            $decoded = Firebase\JWT\JWT::decode($token, new Firebase\JWT\Key($this->secretKey, $this->algorithm));
            return (array) $decoded->data;
        } catch (Exception $e) {
            Yii::log('JWT Validation Error: ' . $e->getMessage(), 'error');
            return null;
        }
    }

    /**
     * Extract token from header
     */
    public function extractToken()
    {
        $headers = getallheaders();
        
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
                return $matches[1];
            }
        }
        
        // Also check query parameter
        if (isset($_GET['token'])) {
            return $_GET['token'];
        }
        
        return null;
    }

    /**
     * Get current user from token
     */
    public function getCurrentUser()
    {
        $token = $this->extractToken();
        if (!$token) {
            return null;
        }
        
        return $this->validateToken($token);
    }

    /**
     * Send JSON response
     */
    public function sendResponse($success, $message, $data = null, $httpCode = 200)
    {
        header('Content-Type: application/json');
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