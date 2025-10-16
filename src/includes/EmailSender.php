<?php
// Cambia esta línea - la ruta correcta es:
require_once __DIR__ . '/../vendor/autoload.php';  // ← CORREGIDO
require_once 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailSender
{
    private $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);

        try {
            // Configuración del servidor
            $this->mailer->isSMTP();
            $this->mailer->Host = SMTP_HOST;
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = SMTP_USERNAME;
            $this->mailer->Password = SMTP_PASSWORD;
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port = SMTP_PORT;

            // Remitente
            $this->mailer->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
            $this->mailer->isHTML(true);

            // PARA DEBUGGING - DESCOMENTA ESTAS LÍNEAS SI HAY PROBLEMAS
            // $this->mailer->SMTPDebug = 2;
            // $this->mailer->Debugoutput = function($str, $level) {
            //     file_put_contents('smtp_debug.log', "$level: $str\n", FILE_APPEND);
            // };

        } catch (Exception $e) {
            error_log("Error configurando PHPMailer: " . $e->getMessage());

            // Guardar error específico
            $errorMsg = "[" . date('Y-m-d H:i:s') . "] Error PHPMailer: " . $e->getMessage() . "\n";
            file_put_contents('phpmailer_errors.log', $errorMsg, FILE_APPEND);
        }
    }
    public function sendVerificationEmail($toEmail, $toName, $verificationToken)
    {
        try {
            // Destinatario
            $this->mailer->addAddress($toEmail, $toName);

            // Asunto
            $this->mailer->Subject = 'Verifica tu email - ' . SITE_NAME;

            // URL de verificación
            $verificationUrl = SITE_URL . 'verify-email.php?token=' . $verificationToken;

            // Cuerpo del mensaje HTML
            $htmlBody = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='utf-8'>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
                    .container { max-width: 600px; margin: 0 auto; background: #ffffff; }
                    .header { background: linear-gradient(135deg, #6366f1, #4f46e5); color: white; padding: 30px 20px; text-align: center; }
                    .content { padding: 30px; background: #f9f9f9; }
                    .button { background: #4CAF50; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 20px 0; font-size: 16px; }
                    .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; background: #fff; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>" . SITE_NAME . "</h1>
                        <p>Verificación de Email</p>
                    </div>
                    <div class='content'>
                        <h2>¡Bienvenido, " . htmlspecialchars($toName) . "!</h2>
                        <p>Gracias por registrarte en " . SITE_NAME . ". Para activar tu cuenta, por favor verifica tu dirección de email haciendo clic en el siguiente botón:</p>
                        
                        <p style='text-align: center;'>
                            <a href='" . $verificationUrl . "' class='button' style='color: white;'>Verificar Mi Email</a>
                        </p>
                        
                        <p>Si el botón no funciona, copia y pega este enlace en tu navegador:</p>
                        <div style='background: #eee; padding: 15px; border-radius: 5px; word-break: break-all; font-size: 14px;'>
                            " . $verificationUrl . "
                        </div>
                        
                        <p><strong>⚠️ Este enlace expirará en 24 horas.</strong></p>
                        
                        <p>Si no te registraste en " . SITE_NAME . ", por favor ignora este email.</p>
                    </div>
                    <div class='footer'>
                        <p>&copy; " . date('Y') . " " . SITE_NAME . ". Todos los derechos reservados.</p>
                    </div>
                </div>
            </body>
            </html>
            ";

            // Versión texto plano
            $textBody = "¡Bienvenido a " . SITE_NAME . "!\n\n" .
                "Hola " . $toName . ",\n\n" .
                "Gracias por registrarte. Para activar tu cuenta, por favor verifica tu dirección de email visitando el siguiente enlace:\n\n" .
                $verificationUrl . "\n\n" .
                "Este enlace expirará en 24 horas.\n\n" .
                "Si no te registraste en " . SITE_NAME . ", por favor ignora este email.\n\n" .
                "Saludos,\nEl equipo de " . SITE_NAME;

            $this->mailer->Body = $htmlBody;
            $this->mailer->AltBody = $textBody;

            // Enviar email
            $this->mailer->send();

            // Guardar log de éxito
            error_log("Email de verificación enviado a: " . $toEmail);
            return true;

        } catch (Exception $e) {
            error_log("Error enviando email a " . $toEmail . ": " . $this->mailer->ErrorInfo);

            // Guardar link en archivo como fallback
            $this->saveVerificationLinkToFile($toEmail, $toName, $verificationUrl, $verificationToken);

            // Guardar en sesión para mostrar al usuario
            $_SESSION['debug_verification_url'] = $verificationUrl;
            $_SESSION['debug_verification_email'] = $toEmail;

            return false;
        }
    }

    private function saveVerificationLinkToFile($email, $name, $url, $token)
    {
        $logMessage = "=== FALLBACK - EMAIL NO ENVIADO ===\n";
        $logMessage .= "Para: " . $email . " (" . $name . ")\n";
        $logMessage .= "Link de verificación: " . $url . "\n";
        $logMessage .= "Token: " . $token . "\n";
        $logMessage .= "Fecha: " . date('Y-m-d H:i:s') . "\n";
        $logMessage .= "=================================\n\n";

        file_put_contents('verification_links_fallback.log', $logMessage, FILE_APPEND);
    }
}
?>