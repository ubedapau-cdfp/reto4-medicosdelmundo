<?php // Inicio del apartado PHP
session_start(); // Inicio de sesion
$base = '/reto4-medicosdelmundo/'; // Ruta base
// Acceso para administradoras (id_rol === 3)
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['id_rol']) || intval($_SESSION['id_rol']) !== 3) {
	header('Location: /reto4-medicosdelmundo/signin.php'); // Redirecciona a la página de inicio de sesión si no es una administradora
	exit(); 
}

require_once __DIR__ . '/../clases/Usuario.php'; // Incluye la clase Usuario 

$mensajeUsuario = ''; // Variable para mensajes de error o éxito al gestionar usuarias
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'guardar_usuaria') { // Verifica si se ha enviado el formulario para guardar una nueva usuaria
    $nombre = trim($_POST['nombre'] ?? ''); // Obtiene el nombre de la usuaria
    $email = trim($_POST['email'] ?? ''); // Obtiene el email de la usuaria
    $password = trim($_POST['password'] ?? ''); // Obtiene la contraseña de la usuaria
    $rol = intval($_POST['rol'] ?? 0); // Obtiene el rol seleccionado para la usuaria (2 para orientadora, 3 para administradora)

    if ($nombre !== '' && $email !== '' && $password !== '' && in_array($rol, [2, 3], true)) { // Verifica que todos los campos estén completos y el rol sea válido
        $usuaria = new Usuario(0, $email, $password, $rol, $nombre); // Crea una nueva instancia de Usuario con los datos proporcionados
        if ($usuaria->crear()) { // Intenta crear la usuaria en la base de datos
            header('Location: panelusuarias.php'); // Redirecciona a la misma página para mostrar la lista actualizada de usuarias
            exit(); 
        }
        $mensajeUsuario = 'No se pudo crear la usuaria. Intenta nuevamente.'; // Mensaje de error
    } else {
        $mensajeUsuario = 'Por favor completa todos los campos correctamente.'; // Mensaje de error
    }
}

?>
<!doctype html>
<html lang="es"> <!-- Idioma español -->
<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> <!-- Inclusión de Font Awesome para los íconos -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Menú de Administradora</title>
	<link rel="stylesheet" href="<?= $base ?>estilos.css">
	<link rel="icon" type="image/png" href="<?= $base ?>Imagenes/Logoreal.png"> <!-- Favicon -->
</head>
<body>
<?php include '../barrasNavegacion/headeradmin.php'; ?> <!-- Inclusión del header de admin -->
<?php 
require_once '../conexion.php'; // Inclusión del archivo de conexión a la base de datos
try{
$database = new Database(); // Creación de una instancia de la clase Database
$conn = $database->conectar(); // Conexión a la base de datos

$sql = "SELECT id_usuario, nombre, email, id_rol FROM USUARIOS"; // Consulta SQL para obtener los datos de las usuarias registradas
$stmt = $conn->prepare($sql); // Preparación de la consulta SQL
$stmt->execute(); // Ejecución de la consulta SQL

$usuarios = $stmt->fetchAll(); // Obtención de los resultados de la consulta y almacenamiento en la variable $usuarios

} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage(); // Mensaje de error 
}

?>
<section class="listadousuarias">
<h2 class="titulousuarias">Gestión de usuarias registradas</h2>
<a class="menu-add-btn" href="panelusuarias.php?nueva_usuaria=1">+ Añadir usuaria</a> <!-- Botón para añadir una nueva usuaria -->

<?php if (isset($_GET['nueva_usuaria'])): ?> <!-- Si se ha solicitado añadir una nueva usuaria, muestra el formulario -->
    <section class="gestion-section">
        <h3>Añadir nueva usuaria</h3>
        <?php if ($mensajeUsuario): ?> <!-- Si hay un mensaje de error o éxito, lo muestra -->
            <p class="error"><?php echo htmlspecialchars($mensajeUsuario); ?></p> <!-- Mensaje de error o éxito -->
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
            <button type="submit" class="savebutton"><i class="fa-solid fa-user-plus"></i> Crear usuaria</button> <!-- Botón para guardar la nueva usuaria -->
            <a class="gestionar-btn" href="panelusuarias.php">Cancelar</a> <!-- Botón para cancelar -->
        </form>
    </section>
<?php endif; ?>

    <?php if (!empty($usuarios)): ?> <!-- Si hay usuarias registradas, muestra la tabla con sus datos. -->
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
                    <?php foreach ($usuarios as $usuario): ?> <!-- Itera sobre cada usuaria y muestra sus datos en una fila de la tabla -->
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['nombre']); ?></td> <!-- Muestra el nombre de la usuaria -->
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td> <!-- Muestra el correo electrónico de la usuaria -->
                            <td><?php echo intval($usuario['id_rol']) === 3 ? 'Administradora' : (intval($usuario['id_rol']) === 2 ? 'Orientadora' : 'Usuario'); ?></td> 
                            <!-- Muestra el rol de la usuaria (Administradora, Orientadora o Usuario) -->
                            <td class="acciones">
                                <a class="editbutton" href="editar_usuaria.php?id=<?php echo intval($usuario['id_usuario']); ?>"><i class="fa-solid fa-pencil"></i>Editar</a>
                                <!-- Botón para editar la usuaria, respecto al ID de la usuaria -->
                                <a class="deletebutton" href="eliminar_usuaria.php?id=<?php echo intval($usuario['id_usuario']); ?>" onclick="return confirm('¿Estás segura que deseas eliminar a esta usuaria?');"><i class="fa-solid fa-trash"></i>Eliminar</a>
                                <!-- Botón para eliminar la usuaria, respecto al ID de la usuaria con confirmación antes de eliminar -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    <?php else: ?> <!-- Si no hay usuarias registradas, muestra un mensaje -->
        <p>No hay usuarias registradas en la base de datos.</p>
    <?php endif; ?>
</section>
</body>
</html>
