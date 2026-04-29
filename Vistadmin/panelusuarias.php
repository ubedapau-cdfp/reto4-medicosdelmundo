<?php
session_start();
$base = '/reto4-medicosdelmundo/';
// Acceso para administradoras (id_rol === 3)
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['id_rol']) || intval($_SESSION['id_rol']) !== 3) {
	header('Location: /reto4-medicosdelmundo/signin.php');
	exit();
}

require_once __DIR__ . '/../clases/Usuario.php';

$mensajeUsuario = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'guardar_usuaria') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $rol = intval($_POST['rol'] ?? 0);

    if ($nombre !== '' && $email !== '' && $password !== '' && in_array($rol, [2, 3], true)) {
        $usuaria = new Usuario(0, $email, $password, $rol, $nombre);
        if ($usuaria->crear()) {
            header('Location: panelusuarias.php');
            exit();
        }
        $mensajeUsuario = 'No se pudo crear la usuaria. Intenta nuevamente.';
    } else {
        $mensajeUsuario = 'Por favor completa todos los campos correctamente.';
    }
}

?>
<!doctype html>
<html lang="es">
<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Menú de Administradora</title>
	<link rel="stylesheet" href="<?= $base ?>estilos.css">
	<link rel="icon" type="image/png" href="<?= $base ?>Imagenes/Logoreal.png">
</head>
<body>
<?php include '../barrasNavegacion/headeradmin.php'; ?>
<?php 
require_once '../conexion.php';
try{
$database = new Database();
$conn = $database->conectar();

$sql = "SELECT id_usuario, nombre, email, id_rol FROM USUARIOS";
$stmt = $conn->prepare($sql);
$stmt->execute();

$usuarios = $stmt->fetchAll();

} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}

?>
<section class="listadousuarias">
<h2 class="titulousuarias">Gestión de usuarias registradas</h2>
<a class="menu-add-btn" href="panelusuarias.php?nueva_usuaria=1">+ Añadir usuaria</a>

<?php if (isset($_GET['nueva_usuaria'])): ?>
    <section class="gestion-section">
        <h3>Añadir nueva usuaria</h3>
        <?php if ($mensajeUsuario): ?>
            <p class="error"><?php echo htmlspecialchars($mensajeUsuario); ?></p>
        <?php endif; ?>
        <form method="post" action="panelusuarias.php" class="gestionar-form">
            <input type="hidden" name="accion" value="guardar_usuaria">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" class="formulario-edit" required>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="formulario-edit" required>
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" class="formulario-edit" required>
            <label for="rol">Rol</label>
            <select id="rol" name="rol" class="formulario-edit" required>
                <option value="2">Orientadora</option>
                <option value="3">Administradora</option>
            </select>
            <button type="submit" class="savebutton"><i class="fa-solid fa-user-plus"></i> Crear usuaria</button>
            <a class="gestionar-btn" href="panelusuarias.php">Cancelar</a>
        </form>
    </section>
<?php endif; ?>

    <?php if (!empty($usuarios)): ?>
        <section class="tabla-contenedor">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo Electrónico</th>
                        <th>Rol</th>
                        <th class="acciones">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td><?php echo intval($usuario['id_rol']) === 3 ? 'Administradora' : (intval($usuario['id_rol']) === 2 ? 'Orientadora' : 'Usuario'); ?></td>
                            <td class="acciones">
                                <a class="editbutton" href="editar_usuaria.php?id=<?php echo intval($usuario['id_usuario']); ?>"><i class="fa-solid fa-pencil"></i>Editar</a>
                                <a class="deletebutton" href="eliminar_usuaria.php?id=<?php echo intval($usuario['id_usuario']); ?>" onclick="return confirm('¿Estás segura que deseas eliminar a esta usuaria?');"><i class="fa-solid fa-trash"></i>Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    <?php else: ?>
        <p>No hay usuarias registradas en la base de datos.</p>
    <?php endif; ?>
</section>
</body>
</html>
