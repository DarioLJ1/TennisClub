<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '666666');
define('DB_NAME', 'tennisclub');

define('APPROOT', dirname(dirname(__FILE__)));
define('URLROOT', 'http://localhost/TennisClub');
define('SITENAME', 'HomeTennis');

define('PUBLICPATH', dirname(APPROOT) . '/public');

define('EMAIL_HOST', 'smtp.gmail.com');
define('EMAIL_USERNAME', 'dariolara25@gmail.com');
define('EMAIL_PASSWORD', 'gxxp zikn itmd bckd');
define('EMAIL_FROM', 'dariolara25@gmail.com');
define('EMAIL_NAME', 'HomeTennis');
define('EMAIL_PORT', 587);

// Cargar Composer autoloader una sola vez
require_once APPROOT . '/../vendor/autoload.php';

// Cargar ayudantes
require_once APPROOT . '/ayudas/url_helper.php';
require_once APPROOT . '/ayudas/ayuda_sesiones.php';

// Autoloader personalizado
spl_autoload_register(function($className) {
    $className = preg_replace('/^app\\\\/', '', $className);
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    $file = APPROOT . DIRECTORY_SEPARATOR . $class . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die('Error de conexión: ' . $e->getMessage());
}