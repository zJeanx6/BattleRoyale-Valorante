<?php
require 'config.php';
require PHPMAILER_PATH . 'PHPMailer.php';
require PHPMAILER_PATH . 'SMTP.php';
require PHPMAILER_PATH . 'Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = PHPMAILER_HOST;
        $mail->SMTPAuth = PHPMAILER_SMTP_AUTH;
        $mail->Username = PHPMAILER_USERNAME;
        $mail->Password = PHPMAILER_PASSWORD;
        $mail->SMTPSecure = PHPMAILER_SMTP_SECURE;
        $mail->Port = PHPMAILER_PORT;

        // Remitente y destinatario
        $mail->setFrom(PHPMAILER_USERNAME, 'Valorante App');
        $mail->addAddress($to);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        echo 'El mensaje ha sido enviado';
    } catch (Exception $e) {
        echo "El mensaje no pudo ser enviado. Error de Mailer: {$mail->ErrorInfo}";
    }
}
?>
