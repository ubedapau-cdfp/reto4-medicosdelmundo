<?php // Inicio del apartado PHP
session_start(); // Inicio de sesión
$base = '/reto4-medicosdelmundo/'; // Ruta base
// Acceso para administradoras (id_rol === 3)
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['id_rol']) || intval($_SESSION['id_rol']) !== 3) { // Si no hay usuario o el rol no es 3 (administradora), redirige al inicio de sesión
	header('Location: /reto4-medicosdelmundo/signin.php'); // Redirige a la página de inicio de sesión
	exit();
}
require_once '../conexion.php'; // Incluye el archivo de conexión a la base de datos
require_once '../clases/Categoria.php'; // Incluye la clase Categoria

$db = new Database(); // Crea una instancia de la clase Database
$conn = $db->conectar(); // Conecta a la base de datos
$categoriasMadre = Categoria::obtenerCategoriasMadre($conn); // Obtiene las categorías madre de la base de datos
?> <!-- Fin del apartado PHP -->
<!doctype html> <!-- Tipo de documento HTML -->
<html lang="es"> <!-- Idioma español -->
<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> <!-- Enlace a la hoja de estilos de Font Awesome para los iconos -->
	<meta charset="utf-8"> <!-- Codificación de caracteres UTF-8 -->
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Menú de Administradora</title> 
	<link rel="stylesheet" href="<?= $base ?>estilos.css"> <!-- CSS -->
	<link rel="icon" type="image/png" href="<?= $base ?>Imagenes/Logoreal.png"> <!-- Favicon -->
</head>
<body class="menu-no-margin">
	<?php include '../barrasNavegacion/headeradmin.php'; ?> <!-- Inclusión del header de admins -->
	<main class="menu-grid-container">
		<section class="menu-page-actions">
			<a class="menu-add-btn" href="<?= $base ?>Vistadmin/gestionar.php?nueva_categoria=1">+ Añadir categoría madre</a> <!-- Botón para añadir una nueva categoría madre -->
		</section>
		<section class="menu-grid">
			<?php foreach ($categoriasMadre as $cat): ?> <!-- Itera sobre cada categoría madre obtenida de la base de datos -->
				<section class="menu-block-wrapper">
					<a class="menu-block" href="<?= $base ?>Vistadmin/gestionar.php?id=<?= $cat->getIdCategoria() ?>"> <!-- Enlace a la página de gestión de la categoría -->
						<section class="block-content">
							<h2 class="titulomenu"><?= htmlspecialchars($cat->getTitulo()) ?></h2> <!-- Título de la categoría -->
							<p class="descripcionmenu"><?= htmlspecialchars($cat->getDescripcion()) ?></p> <!-- Descripción de la categoría -->
						</section>
					</a>
					<section class="menu-block-actions">
						<a class="editbutton" href="<?= $base ?>Vistadmin/gestionar.php?id=<?= $cat->getIdCategoria() ?>&editar_categoria=<?= $cat->getIdCategoria() ?>"><i class="fas fa-pencil"></i> Editar</a> 
						<!-- Botón para editar la categoría	-->
					<form method="post" action="<?= $base ?>Vistadmin/gestionar.php" class="menu-action-form" onsubmit="return confirm('¿Eliminar categoría madre?');"> 
						<!-- Formulario para eliminar la categoría -->
							<input type="hidden" name="accion" value="eliminar_categoria"> <!-- Campo oculto para indicar la acción de eliminar categoría -->
							<input type="hidden" name="id_categoria" value="<?= $cat->getIdCategoria() ?>"> <!-- Campo oculto para enviar el ID de la categoría a eliminar -->
							<button type="submit" class="deletebutton"><i class="fas fa-trash"></i> Eliminar</button> <!-- Botón para enviar el formulario de eliminación -->
						</form>
					</section>
			</section>
			<?php endforeach; ?>
		</section>
	</main>
</body>
</html>

