<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../estilos.css">
    <title>Editar Usuaria</title>
</head>
<body>
<?php
session_start();
$base = '/reto4-medicosdelmundo/';
// Acceso para administradoras (id_rol === 3)
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['id_rol']) || intval($_SESSION['id_rol']) !== 3) {
    header('Location: /reto4-medicosdelmundo/signin.php');
    exit();
}
require_once __DIR__ . '/../clases/Usuario.php';
include '../barrasNavegacion/headeradmin.php';

$error = null;
$usuaria = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $rol = intval($_POST['rol'] ?? 0);

    $usuaria = Usuario::RecuperarByID($id);
    if ($usuaria) {
        $usuaria->setNombre($nombre);
        $usuaria->setEmail($email);
        $usuaria->setRol($rol);

        if ($usuaria->guardar()) {
            header('Location: panelusuarias.php');
            exit();
        }

        $error = 'No se pudo guardar la información.';
    } else {
        $error = 'No se encontró la usuaria.';
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id']) && intval($_GET['id']) > 0) {
        $usuaria = Usuario::RecuperarByID(intval($_GET['id']));
    } else {
        $error = 'No se indicó una usuaria válida para editar.';
    }
}
?>
<section class="editarusuaria">
    <h2 class="titulousuarias">Editar usuaria</h2>

    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if ($usuaria): ?>
        <form method="post" action="editar_usuaria.php">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" class="formulario-edit" value="<?php echo htmlspecialchars($usuaria->getNombre()); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="formulario-edit" value="<?php echo htmlspecialchars($usuaria->getEmail()); ?>" required>

            <label for="rol">Rol</label>
            <select id="rol" name="rol" class="formulario-edit" required>
                <option value="2" <?php echo $usuaria->getRol() === 2 ? 'selected' : ''; ?>>Orientadora</option>
                <option value="3" <?php echo $usuaria->getRol() === 3 ? 'selected' : ''; ?>>Administradora</option>
            </select>

            <input type="hidden" name="id" value="<?php echo intval($usuaria->getId()); ?>">
            <button type='submit' class='savebutton'><i class="fa-solid fa-user-check"></i>Guardar los Cambios de Usuaria</button> <!-- Botón para iniciar sesión -->
        </form>
    <?php endif; ?>
</section>
</body>
</html>