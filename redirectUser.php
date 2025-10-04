// Redirigir según tipo de usuario
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
// Verificar si el dueño está aprobado
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