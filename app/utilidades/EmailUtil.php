<?php
namespace app\utilidades;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailUtil {
    private $mail;

    public function __construct() {

        $this->mail = new PHPMailer(true);
        
        try {

            $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $this->mail->isSMTP();
            $this->mail->Host       = EMAIL_HOST;
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = EMAIL_USERNAME;
            $this->mail->Password   = EMAIL_PASSWORD;
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port       = EMAIL_PORT;

            $this->mail->CharSet = 'UTF-8';
            $this->mail->Encoding = 'base64';

            
            $this->mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $this->mail->setFrom(EMAIL_FROM, EMAIL_NAME);

            error_log("PHPMailer configurado con los siguientes datos:");
            error_log("Host: " . EMAIL_HOST);
            error_log("Puerto: " . EMAIL_PORT);
            error_log("Usuario: " . EMAIL_USERNAME);
            error_log("Remitente: " . EMAIL_FROM);

        } catch (Exception $e) {

            error_log("Error al configurar PHPMailer: {$this->mail->ErrorInfo}");

        }
    }

    public function enviarEmail($destinatario, $asunto, $cuerpo) {

        try {

            $this->mail->addAddress($destinatario);
            $this->mail->isHTML(true);
            $this->mail->Subject = $asunto;
            $this->mail->Body    = $cuerpo;

            error_log("Intentando enviar correo a: $destinatario");
            error_log("Asunto: $asunto");
            error_log("Cuerpo: $cuerpo");

            if ($this->mail->send()) {

                error_log("Correo enviado exitosamente a: $destinatario");
                return true;

            } else {
                error_log("Error al enviar email: " . $this->mail->ErrorInfo);
                return false;
            }

        } catch (Exception $e) {

            error_log("Excepción al enviar email a $destinatario: " . $e->getMessage());
            error_log("Detalles del error: " . $this->mail->ErrorInfo);
            return false;

        }
    }

    public function sendTestEmail($to) {

        $subject = 'Prueba de Email de HomeTennis';
        $body = '<h1>Test Email</h1><p>Funciona Correctamente</p>';
        return $this->enviarEmail($to, $subject, $body);
        
    }
}