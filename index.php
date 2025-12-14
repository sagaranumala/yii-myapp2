

<?php
// Simple index.php for Docker

// Yii settings
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

// Paths in Docker container
$yii = '/var/www/yii/framework/yii.php';
$config = dirname(__FILE__) . '/protected/config/main.php';

// Load Yii
require_once($yii);

// Run application
Yii::createWebApplication($config)->run();

