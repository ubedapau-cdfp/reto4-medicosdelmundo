<?php // Inicio del apartado PHP 
session_start(); // Inicio de la sesión
$base = '/reto4-medicosdelmundo/'; // Ruta base para enlaces y recursos
?> <!-- Fin del apartado PHP -->
<!DOCTYPE html> <!-- Inicio del apartado HTML -->
<html lang="es">
<head> <!-- Inicio del apartado head -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inicio</title>
<link rel="icon" type="image/png" href="<?= $base ?>Imagenes/Logoreal.png">
<link rel="stylesheet" href="estilos.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> <!-- Enlace a la biblioteca de iconos Font Awesome para usar íconos en la página -->
</head> <!-- Fin del apartado head -->
<body> <!-- Inicio del apartado body -->
<?php // Inicio del apartado PHP
if (!isset($_SESSION['id_rol']) || intval($_SESSION['id_rol']) === 1) { // Acceso para usuarias normales (id_rol === 1)
    include 'barrasNavegacion/header.php'; // Header normal
} elseif (in_array(intval($_SESSION['id_rol']), [2, 3], true)) { // Acceso para orientadoras y administradoras (id_rol === 2, 3)
    include 'barrasNavegacion/headeradmin.php'; // Header de administradoras y de orientadoras
} else {
    include 'barrasNavegacion/header.php'; // Header normal
}
?> <!-- Fin del apartado PHP -->
<section class="intro">
    &nbsp; <!-- Non-breaking space, usado para salto de línea -->
    <p class="texto-inicio">Bienvenidas a la página web de Médicos del Mundo</p> <!-- Párrafo de texto, como título, con mensaje de bienvenida -->
    <p class="subtexto-inicio">Esperemos que disfruten su estancia.</p> <!-- Párrafo de texto, como subtítulo, con mensaje de bienvenida -->
    <section class="imagenes"> <!-- Sección imágenes #1 -->
        <img src="Imagenes/imageninicio1.jpg" alt="Imagen 1" width="600" height="400"> <!-- Imagen para el home -->
        <img src="Imagenes/imageninicio2.jpg" alt="Imagen 1" width="600" height="400"> <!-- Imagen para el home -->
    </section>
    <section class="imagenes2"> <!-- Sección imágenes #2 -->
        <img src="Imagenes/imageninicio3.jpg" alt="Imagen 1" width="600" height="400"> <!-- Imagen para el home -->
        <img src="Imagenes/imageninicio4.jpg" alt="Imagen 1" width="600" height="400"> <!-- Imagen para el home -->
</section>
<?php include 'barrasNavegacion/footer.php'; ?> <!-- Inclusión del footer en la página -->
</body> <!-- Fin del apartado body -->
</html> <!-- Fin del apartado HTML -->