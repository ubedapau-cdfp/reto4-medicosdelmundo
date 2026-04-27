<?php // Inicio del apartado PHP
session_start(); // Iniciar sesión para gestionar roles de usuario
$base = '/reto4-medicosdelmundo/'; // valor $base equivale a la ruta absoluta para su uso en la página
?> <!-- Cierre del apartado PHP --> 
<!DOCTYPE html> <!-- Tipo de documento HTML -->
<html lang="es"> <!-- Inicio del HTML -->
<head> <!-- Inicio del head -->
<meta charset="UTF-8"> <!-- Juego de carácteres para visualización correcta. Tipo UTF-8 -->
<meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Tamaño y forma del viewport -->
<title>Inicio</title> <!-- Título para la parte superior -->
<link rel="icon" type="image/png" href="<?= $base ?>Imagenes/Logoreal.png"> <!-- Icono para la parte superior -->
<link rel="stylesheet" href="../estilos.css"> <!-- Conexión con el CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> <!-- Conexión con la librería de iconos Font Awesome -->
</head> <!-- Cierre del head -->
<body> <!-- Inicio del body -->
<?php // Header importado externamente mediante PHP para mostrar diferentes barras de navegación según el rol del usuario
if (!isset($_SESSION['id_rol']) || intval($_SESSION['id_rol']) === 1) {
    include '../barrasNavegacion/header.php';  // Si no hay sesión iniciada o el rol es 1, se muestra el header.php 
} elseif (in_array(intval($_SESSION['id_rol']), [2, 3], true)) {
    include '../barrasNavegacion/headeradmin.php'; // Si el rol es 2 o 3, se muestra el headeradmin.php
} else {
    include '../barrasNavegacion/header.php'; // Para cualquier otro caso, se muestra el header.php
}
?> <!-- Cierre del apartado PHP -->
<section class="intro"> <!-- Inicio del section intro -->
    &nbsp; <!-- ESPACIO EN BLANCO -->
    <p class="texto-inicio">Bienvenidas a la página web de Médicos del Mundo</p> <!-- Párrafo de texto -->
    <p class="subtexto-inicio">Esperemos que disfruten su estancia.</p> <!-- Párrafo de texto -->
    <section class="imagenes"> <!-- Inicio del section imagenes -->
        <img src="../Imagenes/imageninicio1.jpg" alt="Personas de Médicos del Mundo hablando con una mujer" width="600" height="400"> <!-- Imagen con su ruta, texto alternativo, ancho y alto -->
        <img src="../Imagenes/imageninicio2.jpg" alt="Personas de Médicos del Mundo sentadas en una mesa de un instituto" width="600" height="400"> <!-- Imagen con su ruta, texto alternativo, ancho y alto -->
    </section> <!-- Cierre del section imagenes -->
    <section class="imagenes2"> <!-- Inicio del section imagenes2 -->
        <img src="../Imagenes/imageninicio3.jpg" alt="Dos mujeres de Médicos del Mundo paseando por la calle" width="600" height="400"> <!-- Imagen con su ruta, texto alternativo, ancho y alto -->
        <img src="../Imagenes/imageninicio4.jpg" alt="Dos personas de Médicos del Mundo paseando por la calle" width="600" height="400"> <!-- Imagen con su ruta, texto alternativo, ancho y alto -->
    </section> <!-- Cierre del section imagenes2 --> 
</section> <!-- Cierre del section intro -->
<?php include '../barrasNavegacion/footer.php'; ?> <!-- Footer importado externamente mediante PHP -->
</body> <!-- Cierre del body -->
</html> <!-- Cierre del HTML -->