<?php // Inicio del apartado PHP
if (session_status() === PHP_SESSION_NONE) session_start();
$base = '/reto4-medicosdelmundo/'; // Valor $base equivale a la ruta absoluta para su uso en la página
?> <!-- Cierre del apartado PHP -->
<header>
		<a href="<?= $base ?>index.php" class="logo"> <!-- Redirección al home -->
			<img src="<?= $base ?>Imagenes/Logoreal.png" alt="Logo"> <!-- Logo de la página -->
		</a>
		<?php // Inicio del apartado PHP
			if (intval($_SESSION['id_rol']) === 2) { // Si el rol de la usuaria es orientadora (id_rol === 2)
				echo '<a class="logoutbutton" href="' . $base . 'VistaOrientadora/Menu.php"><i class="fa-solid fa-house"></i>Menú de Orientadora</a>'; // Redirección al menú de orientadora
			} elseif (intval($_SESSION['id_rol']) === 3) { // Si el rol de la usuaria es administradora (id_rol === 3)
				echo '<a class="logoutbutton" href="' . $base . 'Vistadmin/Menu.php"><i class="fa-solid fa-house"></i>Menú de Administradora</a>'; // Redirección al menú de administradora
				echo '<a class="logoutbutton" href="' . $base . 'Vistadmin/panelusuarias.php" class="gestion-header-button"><i class="fa-solid fa-users-gear"></i>Gestión de Usuarias</a>'; // Redirección a la gestión de usuarias
			}
		?> <!-- Cierre del apartado PHP -->

		<section class="admin-session"> 
			<?php // Inicio del apartado PHP
			if (isset($_SESSION['usuario_nombre'])){ // Si el nombre de la usuaria está definido en la sesión
				$nombre = basename($_SESSION['usuario_nombre']); // Obtener el nombre de la usuaria en variable $nombre
				echo "<span class='admin-name'>Hola, " . $nombre . "</span>"; // Saludo con el nombre de la usuaria
				echo "<a class='logoutbutton' href='" . $base . "logout.php'><i class=\"fa-solid fa-right-from-bracket\"></i>Cerrar sesión</a>"; // Botón para cerrar sesión
			}
			?> <!-- Cierre del apartado PHP -->
		</section>
</header> <!-- Cierre del header -->
