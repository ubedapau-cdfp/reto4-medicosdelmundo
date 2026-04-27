<?php // Inicio del apartado PHP
session_start(); // Iniciar sesión para gestionar roles de usuario
$base = '/reto4-medicosdelmundo/'; // valor $base equivale a la ruta absoluta para su uso en la página
?> <!-- Cierre del apartado PHP -->
<!DOCTYPE html> <!-- Tipo de documento HTML -->
<html lang="es"> <!-- Inicio del HTML -->
<head> <!-- Inicio del head -->
<meta charset="UTF-8"> <!-- Juego de carácteres para visualización correcta. Tipo UTF-8 -->
<meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Tamaño y forma del viewport -->
<title>Requisitos para la Relación Laboral</title> <!-- Título para la parte superior -->
<link rel="icon" type="image/png" href="<?= $base ?>Imagenes/Logoreal.png"> <!-- Icono para la parte superior -->
<link rel="stylesheet" href="../estilos.css"> <!-- Conexión con el CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> <!-- Conexión con la librería de iconos Font Awesome -->
</head> <!-- Cierre del head -->
<body> <!-- Inicio del body -->
<?php // Header importado externamente mediante PHP para mostrar diferentes barras de navegación según el rol del usuario
if (!isset($_SESSION['id_rol']) || intval($_SESSION['id_rol']) === 1) {
    include '../barrasNavegacion/header.php'; // Si no hay sesión iniciada o el rol es 1, se muestra el header.php
} elseif (in_array(intval($_SESSION['id_rol']), [2, 3], true)) {
    include '../barrasNavegacion/headeradmin.php'; // Si el rol es 2 o 3, se muestra el headeradmin.php
} else {
    include '../barrasNavegacion/header.php'; // Para cualquier otro caso, se muestra el header.php
}
?> <!-- Cierre del apartado PHP -->
<p></p> <!-- Separador -->
<section class="contenidos"> <!-- Inicio del section contenidos -->
    <p><b>Requisitos para la Relación Laboral</b></p> <!-- Párrafo de texto, título en negrita -->
    <p>Texto de ejemplo. Líneas, líneas. Texto, texto.</p> <!-- Párrafo de texto -->
    &nbsp; <!-- Non-Breaking Space. Proporciona saltos de línea, con un espacio. -->
    <p><b class="subtitulos">· Subtítulo. </b></p> <!-- Párrafo de texto, con negrita clase subtitulos. Subapartados -->
    <p>Texto de ejemplo. Líneas, líneas. Texto, texto.</p> <!-- Párrafo de texto -->
    <p>Texto de ejemplo. Líneas, líneas. Texto, texto..</p> <!-- Párrafo de texto -->
    &nbsp; <!-- Non-Breaking Space. Proporciona saltos de línea, con un espacio. -->
    <p><b class="subtitulos">· Subtítulo.</b></p> <!-- Párrafo de texto, con negrita clase subtitulos. Subapartados -->
    <p>Texto de ejemplo. Líneas, líneas. Texto, texto.</p> <!-- Párrafo de texto -->
    <p>Texto de ejemplo. Líneas, líneas. Texto, texto.</p> <!-- Párrafo de texto --> 
    &nbsp;  <!-- Non-Breaking Space. Proporciona saltos de línea, con un espacio. -->
    <p><strong><i>Ejemplo:</strong></i> Texto de ejemplo. Líneas, líneas. Texto, texto.</p> <!-- Párrafo de texto, en negrita y en italic para el ejemplo, luego normal para el resto del ejemplo. -->
    &nbsp; <!-- Non-Breaking Space. Proporciona saltos de línea, con un espacio. -->
    <p>Revisa los diferentes apartados que tenemos seleccionando uno de los siguientes botones:</p> <!-- Párrafo de texto -->
    &nbsp; <!-- Non-Breaking Space. Proporciona saltos de línea, con un espacio. -->
    <section class="botonera"> <!-- Inicio del section botonera -->
    <a href="#" class="button">Botón</a><a href="#" class="button">Botón</a><a href="#" class="button">Botón</a> 
    </section> <!-- Cierre del section botonera -->
</section> <!-- Cierre del section contenidos -->
<?php include '../barrasNavegacion/footer.php'; ?> <!-- Footer importado externamente mediante PHP -->
</body> <!-- Cierre del body -->
</html> <!-- Cierre del HTML -->