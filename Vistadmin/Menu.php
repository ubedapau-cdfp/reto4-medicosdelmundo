<?php
$base = '/reto4-medicosdelmundo/';
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Menú - Bloques</title>
	<link rel="stylesheet" href="<?= $base ?>estilos.css">
</head>
<body class="menu-no-margin">
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

