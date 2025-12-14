<?php
// index.php - Fixed version

// ==============================================
// ERROR REPORTING - Show all errors
// ==============================================
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Enable debug mode
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

// ==============================================
// DOCKER PATHS
// ==============================================
// Yii is mounted at /var/www/yii in Docker
$yii = '/var/www/yii/framework/yii.php';

// ==============================================
// Verify Yii exists
// ==============================================
if (!file_exists($yii)) {
    echo '<!DOCTYPE html><html><head><title>Error</title></head><body>';
    echo '<h2>Docker Debug: Yii Framework Not Found</h2>';
    echo '<p>Expected path: ' . htmlspecialchars($yii) . '</p>';
    echo '<p>Current directory: ' . getcwd() . '</p>';
    echo '</body></html>';
    die();
}

// ==============================================
// Load configuration
// ==============================================
$config = dirname(__FILE__) . '/protected/config/main.php';

if (!file_exists($config)) {
    echo '<!DOCTYPE html><html><head><title>Error</title></head><body>';
    echo 'Config file not found: ' . $config;
    echo '</body></html>';
    die();
}

// ==============================================
// Load Yii and run application
// ==============================================
try {
    require_once($yii);
    
    // Set Yii path aliases for Docker
    Yii::setPathOfAlias('system', '/var/www/yii/framework');
    Yii::setPathOfAlias('application', dirname(__FILE__) . '/protected');
    
    $app = Yii::createWebApplication($config);
    $app->run();
} catch (Exception $e) {
    echo '<!DOCTYPE html><html><head><title>Application Error</title></head><body>';
    echo '<h2>Application Error</h2>';
    echo '<p><strong>Error:</strong> ' . $e->getMessage() . '</p>';
    echo '<p><strong>File:</strong> ' . $e->getFile() . '</p>';
    echo '<p><strong>Line:</strong> ' . $e->getLine() . '</p>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
    echo '</body></html>';
}

