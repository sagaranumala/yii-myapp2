<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// Load environment variables (Docker)
$env = getenv();

defined('APP_ENV') or define('APP_ENV', isset($env['APP_ENV']) ? $env['APP_ENV'] : 'dev');
defined('DB_HOST') or define('DB_HOST', isset($env['DB_HOST']) ? $env['DB_HOST'] : 'localhost');
defined('DB_NAME') or define('DB_NAME', isset($env['DB_NAME']) ? $env['DB_NAME'] : '');
defined('DB_USER') or define('DB_USER', isset($env['DB_USER']) ? $env['DB_USER'] : '');
defined('DB_PASS') or define('DB_PASS', isset($env['DB_PASS']) ? $env['DB_PASS'] : '');

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'My Web Application',
    
    // ==============================================
    // IMPORTANT: Add system imports for Docker
    // ==============================================
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.controllers.*',
        'system.*',                    // Import Yii system classes
        'system.web.*',                // Import web components
        'system.web.actions.*',        // Import actions (includes CErrorAction)
        'system.web.widgets.*',        // Import widgets
        'system.validators.*',         // Import validators
        'zii.widgets.*',           // Add this for CJuiDatePicker
        'zii.widgets.jui.*',  
    ),

    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'sgii123', // set a secure password
            'ipFilters' => array('*'), // restrict access
        ),
    ),

    'components' => array(
        'jwt' => array(
            'class' => 'JwtHelper',
            'secretKey' => getenv('JWT_SECRET') ?: 'your-super-secret-jwt-key-2024',
            'expireTime' => 86400, // 24 hours
        ),
        'user' => array(
            'allowAutoLogin' => true,
        ),
        
        'urlManager' => array(
            'urlFormat' => 'get',
            'showScriptName' => true,
            'rules' => array(
                // Auth routes
                'auth/login' => 'auth/login',
                'auth/register' => 'auth/register',
                'auth/refresh' => 'auth/refresh',
                'auth/profile' => 'auth/profile',
                'auth/logout' => 'auth/logout',

                // Specific routes first
                'calculator' => 'site/simpleCalc',
                'calc' => 'site/simpleCalc',
                'test-db' => 'site/testDb',
                
                // API routes
                'api/users' => 'api/getUsers',
                'api/users/<id:\d+>' => 'api/getUser',
                'api/users/email/<email:.*>' => 'api/getUserByEmail',
                'api/users/userId/<userId:.*>' => 'api/getUserByUserId',
                'api/users/create' => 'api/createUser',
                'api/users/update/<id:\d+>' => 'api/updateUser',
                'api/users/delete/<id:\d+>' => 'api/deleteUser',

                // ========== BLOG ROUTES ==========
                // Blog listing (public)
                'blogs' => 'blog/index',
                
                // View single blog (public)
                'blogs/<id:\d+>' => 'blog/view',
                
                // Create new blog (logged-in users)
                'blogs/create' => 'blog/create',
                
                // Edit blog (author or admin)
                'blogs/update/<id:\d+>' => 'blog/update',
                
                // Delete blog (admin only)
                'blogs/delete/<id:\d+>' => 'blog/delete',
                
                // My blogs (logged-in users)
                'blogs/my' => 'blog/myBlogs',
                'blogs/my-blogs' => 'blog/myBlogs',
                
                // Blog API routes (if needed)
                'api/blogs' => 'api/getBlogs',
                'api/blogs/<id:\d+>' => 'api/getBlog',
                'api/blogs/create' => 'api/createBlog',
                'api/blogs/update/<id:\d+>' => 'api/updateBlog',
                'api/blogs/delete/<id:\d+>' => 'api/deleteBlog',
                
                // Blog search (optional)
                'blogs/search' => 'blog/search',
                'blogs/category/<category:\w+>' => 'blog/category',
                
                // ================================
                
                // Default routes - MUST BE LAST
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        
        'db' => require(dirname(__FILE__) . '/database.php'),
        
        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
        
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),
    ),
    
    'params' => array(
        'adminEmail' => 'webmaster@example.com',
        'salt' => 'your-secret-salt-string',
        'jwtSecret' => 'your-super-secret-jwt-key-32-chars-minimum!',
    ),
);