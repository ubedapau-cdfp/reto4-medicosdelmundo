<?php // Inicio del apartado PHP
if (session_status() === PHP_SESSION_NONE) session_start();
$base = '/reto4-medicosdelmundo/'; // Valor $base equivale a la ruta absoluta para su uso en la página
?> <!-- Cierre del apartado PHP -->
<header>
		<a href="<?= $base ?>home/home.php" class="logo">
			<img src="<?= $base ?>Imagenes/Logoreal.png" alt="Logo">
		</a>
		<section class="admin-session">
			<?php
			if (isset($_SESSION['usuario_nombre'])) {
				$nombre = basename($_SESSION['usuario_nombre']);;
				echo "<span class='admin-name'>Hola, " . $nombre . "</span>";
				echo "<button class='logoutbutton'>"; // Botón para cerrar sesión
				echo "<a href='" . $base . "logout.php'>Cerrar sesión</a>";
				echo "</button>";
			}
			?>
	</section>
</header>