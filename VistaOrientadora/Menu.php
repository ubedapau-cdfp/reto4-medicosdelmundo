<?php
session_start();
$base = '/reto4-medicosdelmundo/';
// Acceso sólo para orientadoras (id_rol === 2)
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['id_rol']) || intval($_SESSION['id_rol']) !== 2) {
	header('Location: /reto4-medicosdelmundo/signin.php');
	exit();
}
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Menú - Orientadora</title>
	<link rel="stylesheet" href="<?= $base ?>estilos.css">
	<link rel="icon" type="image/png" href="<?= $base ?>Imagenes/Logoreal.png">
</head>
<body class="menu-no-margin">
	<header class="admin-top">
		<a href="<?= $base ?>home/home.php" class="logo">
			<img src="<?= $base ?>Imagenes/Logoreal.png" alt="Logo">
		</a>
		<section class="admin-session">
			<?php
			if (isset($_SESSION['usuario_nombre'])) {
				$nombre = basename($_SESSION['usuario_nombre']);;
				echo "<span class='admin-name'>Hola, " . $nombre . "</span>";
				echo "<a class='logout-button' href='" . $base . "logout.php'>Cerrar sesión</a>";
			}
			?>
		</section>
	</header>
	<main class="menu-grid">
		<a class="menu-block block-contratos" href="<?= $base ?>contratos/relacionlaboral.php">
			<section class="block-content">
				<h2>Contratos</h2>
				<p>Requisitos para la Relación Laboral y normativa</p>
			</section>
		</a>
    
		<a class="menu-block block-elementos" href="<?= $base ?>elementosYcondiciones/elementossustanciales.php">
			<section class="block-content">
				<h2>Elementos y Condiciones</h2>
				<p>Elementos sustanciales e importantes</p>
			</section>
		</a>

		<a class="menu-block block-proceso" href="<?= $base ?>procesoDeContratacionRequisitos/obligacionesempresa.php">
			<section class="block-content">
				<h2>Proceso y Requisitos</h2>
				<p>Obligaciones de la empresa y requisitos de la trabajadora</p>
			</section>
		</a>

		<a class="menu-block block-relacion" href="<?= $base ?>RelacionLaboral/definicionyrequisitos.php">
			<section class="block-content">
				<h2>Relación Laboral</h2>
				<p>Definición, principios y requisitos</p>
			</section>
		</a>
	</main>
</body>
</html>
