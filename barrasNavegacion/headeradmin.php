<?php // Inicio del apartado PHP
if (session_status() === PHP_SESSION_NONE) session_start();
$base = '/reto4-medicosdelmundo/'; // Valor $base equivale a la ruta absoluta para su uso en la página
?> <!-- Cierre del apartado PHP -->
<header>
		<a href="<?= $base ?>home/home.php" class="logo">
			<img src="<?= $base ?>Imagenes/Logoreal.png" alt="Logo">
		</a>
		<?php
			if (intval($_SESSION['id_rol']) === 2) {
				echo '<button class="logoutbutton"><a href="' . $base . 'VistaOrientadora/Menu.php"><i class="fa-solid fa-house"></i>Menú de Orientadora</a></button>';
			} elseif (intval($_SESSION['id_rol']) === 3) {
				echo '<button class="logoutbutton"><a href="' . $base . 'Vistadmin/Menu.php"><i class="fa-solid fa-house"></i>Menú de Administradora</a></button>';
			}
		?>
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