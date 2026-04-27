<?php // Inicio del apartado PHP - abre el bloque PHP para lógica del servidor
if (session_status() === PHP_SESSION_NONE) session_start(); // Inicia la sesión si no está iniciada
$base = '/reto4-medicosdelmundo/'; // Ruta base usada para construir URLs relativas en la aplicación
?> <!-- Cierre del apartado PHP - volvemos a HTML -->
<header> <!-- Inicio de la cabecera: logo, menús y sesión -->
	<a href="<?= $base ?>home/home.php" class="logo"> <!-- Enlace al home usando la variable $base -->
		<img src="<?= $base ?>Imagenes/Logoreal.png" alt="Logo"> <!-- Imagen del logo de la ONG -->
	</a> <!-- Cierre del enlace del logo -->
	<?php // Inicio bloque PHP: mostrar enlaces según rol del usuario
		if (intval($_SESSION['id_rol']) === 2) { // Si el rol es 2 (Orientadora)
			echo '<a class="logoutbutton" href="' . $base . 'VistaOrientadora/Menu.php"><i class="fa-solid fa-house"></i>Menú de Orientadora</a>'; // Mostrar enlace al menú de orientadora
		} elseif (intval($_SESSION['id_rol']) === 3) { // Si el rol es 3 (Administradora)
			echo '<a class="logoutbutton admin-menu-btn" href="' . $base . 'Vistadmin/Menu.php"><i class="fa-solid fa-house"></i>Menú de Administradora</a>'; // Mostrar enlace al menú de administradora
		} // Fin condicional roles
	?>
	<section class="admin-session"> <!-- Sección para mostrar nombre de usuario y acciones de sesión -->
		<?php // Inicio bloque PHP: saludo y enlace de logout si hay usuario logueado
		if (isset($_SESSION['usuario_nombre'])) { // Comprueba si existe la sesión con el nombre de usuario
			$nombre = basename($_SESSION['usuario_nombre']); // Obtener el nombre desde la sesión (basename por seguridad)
			echo "<span class='admin-name'>Hola, " . $nombre . "</span>"; // Mostrar saludo con el nombre del usuario
			echo '<a class="logoutbutton" href="' . $base . 'logout.php"><i class="fa-solid fa-right-from-bracket"></i>Cerrar sesión</a>'; // Enlace para cerrar sesión
		} // Fin comprobación usuario
		?>
	</section> <!-- Cierre sección admin-session -->
</header> <!-- Cierre del header -->