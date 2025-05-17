<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/utilidades/EmailUtil.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use app\utilidades\EmailUtil;

echo "Diagnóstico de PHPMailer y entorno PHP\n\n";

// Versión de PHP
echo "Versión de PHP: " . phpversion() . "\n";

// Extensiones cargadas
echo "\nExtensiones PHP cargadas:\n";
$loaded_extensions = get_loaded_extensions();
echo implode(", ", $loaded_extensions) . "\n";

// Verificar si OpenSSL está cargado
echo "\nOpenSSL cargado: " . (in_array('openssl', $loaded_extensions) ? 'Sí' : 'No') . "\n";
if (in_array('openssl', $loaded_extensions)) {
    echo "Versión de OpenSSL: " . OPENSSL_VERSION_TEXT . "\n";
}

// Configuración de SMTP
echo "\nConfiguración SMTP:\n";
echo "Host: " . EMAIL_HOST . "\n";
echo "Puerto: " . EMAIL_PORT . "\n";
echo "Usuario: " . EMAIL_USERNAME . "\n";
echo "Contraseña: " . str_repeat('*', strlen(EMAIL_PASSWORD)) . "\n";

// Intentar conexión SMTP
echo "\nIntentando conexión SMTP...\n";
$smtp = new SMTP;
try {
    $smtp->connect(EMAIL_HOST, EMAIL_PORT);
    echo "Conexión exitosa al servidor SMTP.\n";
    
    if ($smtp->hello(gethostname())) {
        echo "HELO/EHLO exitoso.\n";
        if ($smtp->authenticate(EMAIL_USERNAME, EMAIL_PASSWORD)) {
            echo "Autenticación exitosa.\n";
        } else {
            echo "Fallo en la autenticación.\n";
        }
    } else {
        echo "Fallo en HELO/EHLO.\n";
    }
} catch (Exception $e) {
    echo "Error de conexión SMTP: " . $e->getMessage() . "\n";
}

// Intentar enviar un correo de prueba
echo "\nIntentando enviar un correo de prueba...\n";
$emailUtil = new EmailUtil();
$result = $emailUtil->sendTestEmail(EMAIL_USERNAME);

if ($result) {
    echo "Correo de prueba enviado con éxito.\n";
} else {
    echo "Fallo al enviar el correo de prueba. Revisa los logs para más detalles.\n";
}

echo "\nDiagnóstico completado.\n";

