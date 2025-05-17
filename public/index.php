<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('ROOTPATH', dirname(__DIR__));

require_once ROOTPATH . '/app/config/config.php';

try {
    $app = new \app\librerias\Core();
    $app->run();
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
    echo "Trace: <pre>" . $e->getTraceAsString() . "</pre>";
}





