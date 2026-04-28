<?php
session_start();
$base = '/reto4-medicosdelmundo/';
// Acceso para administradoras (id_rol === 3)
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['id_rol']) || intval($_SESSION['id_rol']) !== 3) {
	header('Location: /reto4-medicosdelmundo/signin.php');
	exit();
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
    
    <?php if (!empty($usuarios)): ?>
        <div class="tabla-contenedor">
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
        </div>
    <?php else: ?>
        <p>No hay usuarias registradas en la base de datos.</p>
    <?php endif; ?>
</section>
</body>
</html>
