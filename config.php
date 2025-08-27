<?php
// config.php - Configuración centralizada del sistema

// ==================== CONFIGURACIÓN DEL SISTEMA ====================
define('SISTEMA_NOMBRE', 'Shopping Promociones');
define('SISTEMA_VERSION', '1.0.0');
define('MODO_SIMULACION', true); // Cambiar a false cuando tengas BD real

// ==================== CONFIGURACIÓN DE BASE DE DATOS ====================
if (MODO_SIMULACION) {
    // Modo simulación (sin BD real) - DATOS DE PRUEBA
    class DatabaseSimulada {
        private $promociones = [];
        private $usuarios = [];
        private $locales = [];
        
        public function __construct() {
            $this->inicializarDatosPrueba();
        }
        
        private function inicializarDatosPrueba() {
            // Usuarios de prueba
            $this->usuarios = [
                'admin@shopping.com' => [
                    'id' => 1,
                    'nombre' => 'Administrador Demo',
                    'email' => 'admin@shopping.com',
                    'password' => 'admin123', // En producción usar hash
                    'rol' => 'administrador',
                    'categoria' => '',
                    'estado' => 'activo'
                ],
                'cliente@demo.com' => [
                    'id' => 2,
                    'nombre' => 'Cliente Demo', 
                    'email' => 'cliente@demo.com',
                    'password' => 'cliente123',
                    'rol' => 'cliente',
                    'categoria' => 'Inicial',
                    'estado' => 'activo'
                ],
                'dueno@demo.com' => [
                    'id' => 3,
                    'nombre' => 'Dueño Demo',
                    'email' => 'dueno@demo.com',
                    'password' => 'dueno123',
                    'rol' => 'dueño',
                    'categoria' => '',
                    'estado' => 'activo'
                ]
            ];
            
            // Locales de prueba
            $this->locales = [
                1 => [
                    'id' => 1,
                    'nombre' => 'Tienda Fashion',
                    'ubicacion' => 'Planta Baja - Local 12',
                    'rubro' => 'Indumentaria',
                    'dueño_id' => 3,
                    'estado' => 'activo',
                    'codigo' => 'LOC-001'
                ],
                2 => [
                    'id' => 2,
                    'nombre' => 'Calzados Premium',
                    'ubicacion' => 'Primer Piso - Local 45', 
                    'rubro' => 'Calzado',
                    'dueño_id' => 3,
                    'estado' => 'activo',
                    'codigo' => 'LOC-002'
                ]
            ];
            
            // Promociones de prueba
            $this->promociones = [
                [
                    'id' => 1,
                    'texto' => '20% DE DESCUENTO EN EFECTIVO - [MODO DEMO]',
                    'local_id' => 1,
                    'local_nombre' => 'Tienda Fashion',
                    'fecha_desde' => '2025-08-01',
                    'fecha_hasta' => '2025-09-30',
                    'categoria' => 'Inicial',
                    'estado' => 'aprobada',
                    'dias' => ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'],
                    'imagen' => 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80'
                ],
                [
                    'id' => 2,
                    'texto' => '2x1 EN PRODUCTOS SELECCIONADOS - [MODO DEMO]',
                    'local_id' => 2,
                    'local_nombre' => 'Calzados Premium',
                    'fecha_desde' => '2025-09-01',
                    'fecha_hasta' => '2025-10-15',
                    'categoria' => 'Medium', 
                    'estado' => 'aprobada',
                    'dias' => ['Sábado', 'Domingo'],
                    'imagen' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80'
                ]
            ];
        }
        
        // Métodos para obtener datos
        public function getPromociones() {
            return $this->promociones;
        }
        
        public function getUsuario($email) {
            return isset($this->usuarios[$email]) ? $this->usuarios[$email] : false;
        }
        
        public function getLocal($id) {
            return isset($this->locales[$id]) ? $this->locales[$id] : false;
        }
        
        public function getLocales() {
            return $this->locales;
        }
        
        public function login($email, $password) {
            if (isset($this->usuarios[$email]) && $this->usuarios[$email]['password'] === $password) {
                return $this->usuarios[$email];
            }
            return false;
        }
    }
    
    // Instancia global de la base de datos simulada
    $db = new DatabaseSimulada();
    
} else {
    // ==================== MODO REAL CON BASE DE DATOS ====================
    $host = getenv('DB_HOST') ?: 'localhost';
    $dbname = getenv('DB_NAME') ?: 'shopping_promociones';
    $username = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASS') ?: '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Aquí irían las funciones reales de BD para después
        
    } catch (PDOException $e) {
        error_log("Error de conexión BD: " . $e->getMessage());
        die("Error del sistema. Por favor, intente más tarde.");
    }
}

// ==================== FUNCIONES GLOBALES ====================
function getPromociones() {
    global $db;
    if (MODO_SIMULACION) {
        return $db->getPromociones();
    } else {
        // Consulta real a BD (para después)
        // $stmt = $pdo->query("SELECT * FROM promociones WHERE estadoPromo = 'aprobada'");
        // return $stmt->fetchAll(PDO::FETCH_ASSOC);
        return [];
    }
}

function getUserByEmail($email) {
    global $db;
    if (MODO_SIMULACION) {
        return $db->getUsuario($email);
    } else {
        // Consulta real a BD
        return false;
    }
}

// ==================== INICIALIZACIÓN DE SESIÓN ====================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==================== MANEJO DE ERRORES ====================
ini_set('display_errors', MODO_SIMULACION ? 1 : 0);
error_reporting(E_ALL);
?>