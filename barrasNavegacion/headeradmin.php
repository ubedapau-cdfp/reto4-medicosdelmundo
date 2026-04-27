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
				echo '<a class="logoutbutton" href="' . $base . 'Vistadmin/gestion_header.php" class="gestion-header-button"><i class="fa-solid fa-screwdriver-wrench"></i>Gestión Header</a>';
			}
		?>
	</section> <!-- Cierre sección admin-session -->
</header> <!-- Cierre del header -->