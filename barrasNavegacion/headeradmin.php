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
				echo '<a class="logoutbutton" href="' . $base . 'VistaOrientadora/Menu.php"><i class="fa-solid fa-house"></i>Menú de Orientadora</a>';
			} elseif (intval($_SESSION['id_rol']) === 3) {
				echo '<a class="logoutbutton" href="' . $base . 'Vistadmin/Menu.php"><i class="fa-solid fa-house"></i>Menú de Administradora</a>';
			}
		?>
		<section class="admin-session">
			<?php
			if (isset($_SESSION['usuario_nombre'])) {
				$nombre = basename($_SESSION['usuario_nombre']);
				echo "<span class='admin-name'>Hola, " . $nombre . "</span>";
				echo '<a class="logoutbutton" href="' . $base . 'logout.php"><i class="fa-solid fa-right-from-bracket"></i>Cerrar sesión</a>';
			}
			?>
	</section>
</header>