<?php
require_once 'database.php';
require_once 'config.php';

class Auth
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Registrar nuevo usuario CON VERIFICACIÓN DE EMAIL
    public function register($email, $password, $tipoUsuario, $nombre = '', $categoria = '')
    {
        try {
            // Verificar si el usuario ya existe
            $query = "SELECT codUsuario FROM usuarios WHERE nombreUsuario = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return "El usuario ya existe";
            }

            // Hash de la contraseña
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Generar token de verificación
            $verificationToken = bin2hex(random_bytes(32));
            $tokenExpiry = date('Y-m-d H:i:s', time() + VERIFICATION_TOKEN_EXPIRY);

            // Estado inicial según tipo de usuario y verificación
            $estado = USER_STATUS_PENDING;
            if (EMAIL_VERIFICATION_REQUIRED) {
                $estado = USER_STATUS_UNVERIFIED;
            }

            // Insertar nuevo usuario
            $query = "INSERT INTO usuarios (nombreUsuario, claveUsuario, tipoUsuario, categoriaCliente, estado, verification_token, token_expires) 
                      VALUES (:email, :password, :tipo, :categoria, :estado, :token, :token_expires)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':tipo', $tipoUsuario);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':token', $verificationToken);
            $stmt->bindParam(':token_expires', $tokenExpiry);

            if ($stmt->execute()) {
                // Enviar email de verificación
                if (EMAIL_VERIFICATION_REQUIRED) {
                    $this->sendVerificationEmail($email, $verificationToken, $nombre);
                }
                return true;
            } else {
                return "Error al registrar usuario";
            }
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Enviar email de verificación CON PHPMailer
// Enviar email de verificación - VERSIÓN CORREGIDA
    private function sendVerificationEmail($email, $token, $nombre)
    {
        // CREAR EL LINK SIEMPRE
        $verificationUrl = SITE_URL . 'verify-email.php?token=' . $token;

        // GUARDAR EN SESIÓN SIEMPRE (no solo cuando falla)
        $_SESSION['debug_verification_url'] = $verificationUrl;
        $_SESSION['debug_verification_email'] = $email;
        $_SESSION['debug_verification_token'] = $token;

        try {
            require_once 'EmailSender.php';
            $emailSender = new EmailSender();
            $result = $emailSender->sendVerificationEmail($email, $nombre, $token);

            if (!$result) {
                // Si falla el envío, guardar en archivo de errores
                $logMessage = "[" . date('Y-m-d H:i:s') . "] Email falló - " . $email . " - Link: " . $verificationUrl . "\n";
                file_put_contents('email_errors.log', $logMessage, FILE_APPEND);
            } else {
                // Si el email se envía, también guardar en log de éxito
                $logMessage = "[" . date('Y-m-d H:i:s') . "] Email enviado - " . $email . " - Link: " . $verificationUrl . "\n";
                file_put_contents('email_success.log', $logMessage, FILE_APPEND);
            }

            return $result;

        } catch (Exception $e) {
            error_log("Error en sendVerificationEmail: " . $e->getMessage());

            // Guardar error en archivo
            $logMessage = "[" . date('Y-m-d H:i:s') . "] EXCEPCIÓN - " . $email . " - Error: " . $e->getMessage() . "\n";
            file_put_contents('email_errors.log', $logMessage, FILE_APPEND);

            return false;
        }
    }

    // Verificar email con token
    public function verifyEmail($token)
    {
        try {
            $query = "SELECT codUsuario, nombreUsuario, token_expires, estado, tipoUsuario 
                      FROM usuarios 
                      WHERE verification_token = :token 
                      AND token_expires > NOW()";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':token', $token);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Actualizar usuario como verificado
                $updateQuery = "UPDATE usuarios 
                               SET estado = :estado, 
                                   verification_token = NULL, 
                                   token_expires = NULL 
                               WHERE codUsuario = :id";
                $updateStmt = $this->conn->prepare($updateQuery);

                // Si es dueño de local, mantener como pendiente para aprobación
                // Si es cliente, activar directamente
                $nuevoEstado = ($user['tipoUsuario'] === USER_OWNER) ? USER_STATUS_PENDING : USER_STATUS_ACTIVE;

                $updateStmt->bindParam(':estado', $nuevoEstado);
                $updateStmt->bindParam(':id', $user['codUsuario']);

                if ($updateStmt->execute()) {
                    return [
                        'success' => true,
                        'email' => $user['nombreUsuario'],
                        'user_type' => $user['tipoUsuario'],
                        'message' => $user['tipoUsuario'] === USER_OWNER ?
                            'Email verificado. Tu cuenta está pendiente de aprobación.' :
                            '¡Email verificado correctamente! Ya puedes iniciar sesión.'
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Token inválido o expirado.'
            ];

        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    // Reenviar email de verificación
    public function resendVerificationEmail($email)
    {
        try {
            $query = "SELECT codUsuario, nombreUsuario, tipoUsuario, estado 
                      FROM usuarios 
                      WHERE nombreUsuario = :email 
                      AND estado = :estado";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':estado', USER_STATUS_UNVERIFIED);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Generar nuevo token
                $newToken = bin2hex(random_bytes(32));
                $tokenExpiry = date('Y-m-d H:i:s', time() + VERIFICATION_TOKEN_EXPIRY);

                $updateQuery = "UPDATE usuarios 
                               SET verification_token = :token, 
                                   token_expires = :expires 
                               WHERE codUsuario = :id";
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->bindParam(':token', $newToken);
                $updateStmt->bindParam(':expires', $tokenExpiry);
                $updateStmt->bindParam(':id', $user['codUsuario']);

                if ($updateStmt->execute()) {
                    $this->sendVerificationEmail($email, $newToken, $user['nombreUsuario']);
                    return true;
                }
            }

            return false;

        } catch (PDOException $e) {
            return false;
        }
    }

    // Iniciar sesión - MODIFICADO para verificar email
    public function login($email, $password)
    {
        try {
            $query = "SELECT codUsuario, nombreUsuario, claveUsuario, tipoUsuario, categoriaCliente, estado 
                  FROM usuarios WHERE nombreUsuario = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verificar contraseña
                if (password_verify($password, $user['claveUsuario'])) {

                    // Verificar si el email está verificado
                    if (EMAIL_VERIFICATION_REQUIRED && $user['estado'] === USER_STATUS_UNVERIFIED) {
                        return "Por favor verifica tu email antes de iniciar sesión. <a href='resend-verification.php?email=" . urlencode($email) . "'>Reenviar email de verificación</a>";
                    }

                    // Verificar si está pendiente (dueños de local)
                    if ($user['estado'] === USER_STATUS_PENDING) {
                        return "Tu cuenta está pendiente de aprobación. Te notificaremos cuando sea activada.";
                    }

                    // Iniciar sesión
                    $_SESSION['user_id'] = $user['codUsuario'];
                    $_SESSION['user_email'] = $user['nombreUsuario'];
                    $_SESSION['user_type'] = $user['tipoUsuario'];
                    $_SESSION['user_category'] = $user['categoriaCliente'];
                    $_SESSION['user_status'] = $user['estado'];

                    return true;
                } else {
                    return "Contraseña incorrecta";
                }
            } else {
                return "Usuario no encontrado";
            }
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Los demás métodos permanecen igual...
    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public function redirectUser()
    {
        if ($this->isLoggedIn()) {
            $base_url = SITE_URL;
            $user_type = $_SESSION['user_type'];
            $user_status = $_SESSION['user_status'];

            switch ($user_type) {
                case USER_ADMIN:
                    header('Location: ' . $base_url . 'admin/panel.php');
                    exit;
                case USER_OWNER:
                    if ($user_status === 'activo') {
                        header('Location: ' . $base_url . 'dueno/panel.php');
                    } else {
                        header('Location: ' . $base_url . 'pendiente.php');
                    }
                    exit;
                case USER_CLIENT:
                    header('Location: ' . $base_url . 'cliente/panel.php');
                    exit;
                default:
                    header('Location: ' . $base_url . 'index.php');
                    exit;
            }
        }
    }

    public function logout()
    {
        $_SESSION = array();
        session_destroy();
        header('Location: ' . SITE_URL . 'login.php');
        exit;
    }

    public function checkAccess($allowedTypes)
    {
        if (!$this->isLoggedIn()) {
            error_log("Usuario no logueado - Redirigiendo a login");
            header('Location: ' . SITE_URL . 'login.php');
            exit;
        }

        error_log("Usuario logueado: Tipo=" . $_SESSION['user_type'] . ", Estado=" . $_SESSION['user_status']);
        error_log("Tipos permitidos: " . implode(', ', $allowedTypes));

        if (!in_array($_SESSION['user_type'], $allowedTypes)) {
            error_log("Tipo de usuario no permitido - Redirigiendo a acceso-denegado");
            header('Location: ' . SITE_URL . 'acceso-denegado.php');
            exit;
        }

        $current_page = basename($_SERVER['PHP_SELF']);
        if (
            $_SESSION['user_type'] === USER_OWNER &&
            $_SESSION['user_status'] !== 'activo' &&
            $current_page === 'panel.php'
        ) {
            error_log("Dueño no activo intentando acceder a panel - Redirigiendo a pendiente.php");
            header('Location: ' . SITE_URL . 'pendiente.php');
            exit;
        }
    }

    public function getCurrentUser()
    {
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'email' => $_SESSION['user_email'],
                'type' => $_SESSION['user_type'],
                'category' => $_SESSION['user_category'],
                'status' => $_SESSION['user_status']
            ];
        }
        return null;
    }

    public function isAdmin()
    {
        return $this->isLoggedIn() && $_SESSION['user_type'] === USER_ADMIN;
    }

    public function isOwner()
    {
        return $this->isLoggedIn() && $_SESSION['user_type'] === USER_OWNER;
    }

    public function isClient()
    {
        return $this->isLoggedIn() && $_SESSION['user_type'] === USER_CLIENT;
    }
}
?>