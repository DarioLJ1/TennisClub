<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/utilidades/EmailUtil.php';

use app\utilidades\EmailUtil;

$emailUtil = new EmailUtil();
$result = $emailUtil->enviarEmail(
    'tu_email@ejemplo.com',
    'Test Email',
    '<h1>Test</h1><p>Este es un correo de prueba.</p>'
);

if ($result) {
    echo "Email enviado correctamente";
} else {
    echo "Error al enviar el email";
}