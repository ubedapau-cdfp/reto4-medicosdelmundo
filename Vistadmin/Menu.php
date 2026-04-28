<?php
session_start();
$base = '/reto4-medicosdelmundo/';
// Acceso para administradoras (id_rol === 3)
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['id_rol']) || intval($_SESSION['id_rol']) !== 3) {
	header('Location: /reto4-medicosdelmundo/signin.php');
	exit();
}
require_once '../conexion.php';
require_once '../clases/Categoria.php';

$db = new Database();
$conn = $db->conectar();
$categoriasMadre = Categoria::obtenerCategoriasMadre($conn);
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
<body class="menu-no-margin">
	<?php include '../barrasNavegacion/headeradmin.php'; ?>
	<main class="menu-grid-container">
		<section class="menu-page-actions">
			<a class="menu-add-btn" href="<?= $base ?>Vistadmin/gestionar.php?nueva_categoria=1">+ Añadir categoría madre</a>
		</section>
		<section class="menu-grid">
			<?php foreach ($categoriasMadre as $cat): ?>
				<section class="menu-block-wrapper">
					<a class="menu-block" href="<?= $base ?>Vistadmin/gestionar.php?id=<?= $cat->getIdCategoria() ?>">
						<section class="block-content">
							<h2 class="titulomenu"><?= htmlspecialchars($cat->getTitulo()) ?></h2>
							<p class="descripcionmenu"><?= htmlspecialchars($cat->getDescripcion()) ?></p>
						</section>
					</a>
					<section class="menu-block-actions">
						<a class="editbutton" href="<?= $base ?>Vistadmin/gestionar.php?id=<?= $cat->getIdCategoria() ?>&editar_categoria=<?= $cat->getIdCategoria() ?>"><i class="fas fa-pencil"></i> Editar</a>
					<form method="post" action="<?= $base ?>Vistadmin/gestionar.php" class="menu-action-form" onsubmit="return confirm('¿Eliminar categoría madre?');">
							<input type="hidden" name="accion" value="eliminar_categoria">
							<input type="hidden" name="id_categoria" value="<?= $cat->getIdCategoria() ?>">
							<button type="submit" class="deletebutton"><i class="fas fa-trash"></i> Eliminar</button>
						</form>
					</section>
			</section>
			<?php endforeach; ?>
		</section>
	</main>
</body>
</html>

