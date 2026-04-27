<?php
$base = '/reto4-medicosdelmundo/';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Preguntas Frecuentes</title>
<link rel="icon" type="image/png" href="<?= $base ?>Imagenes/Logoreal.png">
<link rel="stylesheet" href="../estilos.css">
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
<p>
<section class="contenidos">
    <p><b>Preguntas Frecuentes</b></p>
    <p>Texto de ejemplo. Líneas, líneas. Texto, texto.</p>
    &nbsp; <!-- Non-Breaking Space. Proporciona saltos de línea, con un espacio. -->
    <p><b class="subtitulos">· Subtítulo. </b></p>
    <p>Texto de ejemplo. Líneas, líneas. Texto, texto.</p>
    <p>Texto de ejemplo. Líneas, líneas. Texto, texto..</p>
    &nbsp; <!-- Non-Breaking Space. Proporciona saltos de línea, con un espacio. -->
    <p><b class="subtitulos">· Subtítulo.</b></p>
    <p>Texto de ejemplo. Líneas, líneas. Texto, texto.</p>
    <p>Texto de ejemplo. Líneas, líneas. Texto, texto.</p>
    &nbsp; 
    <p><strong><i>Ejemplo:</strong></i> Texto de ejemplo. Líneas, líneas. Texto, texto.</p>
</section>
<?php include '../barrasNavegacion/footer.php'; ?>

<?php 
//prueba.php
?>

</body>
</html>