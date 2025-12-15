<?php

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
    
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.controllers.*',
    ),

	'modules'=>array(
    'gii'=>array(
        'class'=>'system.gii.GiiModule',
        'password'=>'sgii123', // set a secure password
        'ipFilters'=>array('*'),// restrict access
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
                // 'auth/login' => 'auth/login',
                // 'auth/register' => 'auth/register',
                // 'auth/refresh' => 'auth/refresh',
                // 'auth/profile' => 'auth/profile',
                // 'auth/logout' => 'auth/logout',

                'blogs' => 'blog/index',
            'blog/<id:\d+>' => 'blog/view',
            'blog/create' => 'blog/create',
            'blog/update/<id:\d+>' => 'blog/update',
            'blog/delete/<id:\d+>' => 'blog/delete',
            'my-blogs' => 'blog/myBlogs',
            
            // Alternative routes
            'article/<id:\d+>' => 'blog/view',
            'posts' => 'blog/index',
            'write' => 'blog/create',


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