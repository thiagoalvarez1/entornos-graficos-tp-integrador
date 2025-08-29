<?php
require_once 'database.php';

class Auth
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Registrar nuevo usuario
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

            // Insertar nuevo usuario
            $query = "INSERT INTO usuarios (nombreUsuario, claveUsuario, tipoUsuario, categoriaCliente) 
                      VALUES (:email, :password, :tipo, :categoria)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':tipo', $tipoUsuario);
            $stmt->bindParam(':categoria', $categoria);

            if ($stmt->execute()) {
                return true;
            } else {
                return "Error al registrar usuario";
            }
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Iniciar sesión
    public function login($email, $password)
    {
        try {
            $query = "SELECT codUsuario, nombreUsuario, claveUsuario, tipoUsuario, categoriaCliente 
                      FROM usuarios WHERE nombreUsuario = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verificar contraseña
                if (password_verify($password, $user['claveUsuario'])) {
                    // Iniciar sesión
                    $_SESSION['user_id'] = $user['codUsuario'];
                    $_SESSION['user_email'] = $user['nombreUsuario'];
                    $_SESSION['user_type'] = $user['tipoUsuario'];
                    $_SESSION['user_category'] = $user['categoriaCliente'];

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

    // Verificar si el usuario está logueado
    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    // Redirigir según tipo de usuario
    // includes/auth.php - Busca esta función y reemplázala:

    // Redirigir según tipo de usuario
    // includes/auth.php - Busca la función redirectUser() y REEMPLÁZALA:

    public function redirectUser()
    {
        if ($this->isLoggedIn()) {
            $base_url = SITE_URL;
            $user_type = $_SESSION['user_type'];

            // DEBUG: Mostrar información de redirección
            echo "<script>console.log('Redirecting user type: " . $user_type . "');</script>";

            switch ($user_type) {
                case USER_ADMIN:
                    header('Location: ' . $base_url . 'admin/panel.php');
                    exit;
                case USER_OWNER:
                    header('Location: ' . $base_url . 'dueno/panel.php');
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

    // Cerrar sesión
    public function logout()
    {
        $_SESSION = array();
        session_destroy();
        header('Location: ' . SITE_URL . 'login.php');
        exit;
    }

    // Verificar acceso según tipo de usuario
    public function checkAccess($allowedTypes)
    {
        if (!$this->isLoggedIn()) {
            header('Location: ' . SITE_URL . 'login.php');
            exit;
        }

        if (!in_array($_SESSION['user_type'], $allowedTypes)) {
            header('Location: ' . SITE_URL . 'panel.php');
            exit;
        }
    }
}
?>