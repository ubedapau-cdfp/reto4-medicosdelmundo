<?php
session_start();
$base = '/reto4-medicosdelmundo/';
// Acceso sólo para administradoras (id_rol === 3)
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
<body class="menu-no-margin">
	<header class="admin-top" style="display:flex;align-items:center;justify-content:space-between;padding:10px;">
		<a href="<?= $base ?>home/home.php" class="logo" style="margin-right:10px;">
			<img src="<?= $base ?>Imagenes/Logoreal.png" alt="Logo">
		</a>
		<section style="flex:1;text-align:center;">
			<a href="<?= $base ?>Vistadmin/gestion_header.php" class="gestion-header-button" style="display:inline-block;padding:6px 10px;background:#007bff;color:#fff;border-radius:4px;text-decoration:none;">Gestión Header</a>
		</section>
		<section class="admin-session">
			<?php
			if (isset($_SESSION['usuario_nombre'])) {
				$nombre = basename($_SESSION['usuario_nombre']);
				echo "<span class='admin-name'>Hola, " . $nombre . "</span>";
				echo "<button class='logoutbutton'>"; // Botón para cerrar sesión
				echo "<a href='" . $base . "logout.php'><i class=\"fa-solid fa-right-from-bracket\"></i>Cerrar sesión</a>";
				echo "</button>";
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

