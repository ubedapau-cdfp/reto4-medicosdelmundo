<?php
session_start();
$base = '/reto4-medicosdelmundo/';
// Acceso para administradoras (id_rol === 3) y orientadoras (id_rol === 2)
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['id_rol']) || (intval($_SESSION['id_rol']) !== 3 && intval($_SESSION['id_rol']) !== 2)) {
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
	<main class="menu-grid">
		<?php foreach ($categoriasMadre as $cat): ?>
			<a class="menu-block" href="<?= $base ?>Vistadmin/gestionar.php?id=<?= $cat->getIdCategoria() ?>">
				<section class="block-content">
					<h2><?= htmlspecialchars($cat->getTitulo()) ?></h2>
					<p><?= htmlspecialchars($cat->getDescripcion()) ?></p>
				</section>
			</a>
		<?php endforeach; ?>
	</main>
</body>
</html>

