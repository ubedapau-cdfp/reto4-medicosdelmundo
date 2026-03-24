<?php
$base = '/reto4-medicosdelmundo/';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Definición y Requisitos</title>
<link rel="icon" type="image/png" href="<?= $base ?>Imagenes/Logoreal.png">
<link rel="stylesheet" href="../estilos.css">
</head>
<body>
<?php include '../barrasNavegacion/header.php'; ?>
<p>
<section class="contenidos">
    <p><b>Definición y Requisitos</b></p>
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
    &nbsp;
    <p>Revisa los diferentes apartados que tenemos seleccionando uno de los siguientes botones:</p>
    &nbsp;
    <section class="botonera">
    <a href="#" class="button">Botón</a><a href="#" class="button">Botón</a><a href="#" class="button">Botón</a>
    </section>
</section>
<?php include '../barrasNavegacion/footer.php'; ?>

<?php 
//prueba.php
?>

</body>
</html>