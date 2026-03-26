<?php // Inicio del apartado PHP
$base = '/reto4-medicosdelmundo/'; // Valor $base equivale a la ruta absoluta para su uso en la
?> <!-- Cierre del apartado PHP -->
<!DOCTYPE html> <!-- Tipo de documento HTML -->
<html lang="es"> <!-- Inicio del HTML -->
<head> <!-- Inicio del head -->
<meta charset="UTF-8"> <!-- Juego de carácteres para visualización correcta. Tipo UTF-8 -->
<meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Tamaño y forma del viewport -->
<title>Inicio</title> <!-- Título para la parte superior -->
<link rel="icon" type="image/png" href="<?= $base ?>Imagenes/Logoreal.png"> <!-- Icono para la parte superior -->
<link rel="stylesheet" href="../estilos.css"> <!-- Conexión con el CSS -->
</head> <!-- Cierre del head -->
<body> <!-- Inicio del body -->
<?php include '../barrasNavegacion\header.php'; ?> <!-- Header importado externamente mediante PHP -->
<section class="intro"> <!-- Inicio del section intro -->
    <h1>Bienvenidos a la página</h1> <!-- Párrafo de texto en estilo h1 -->
    <p>Disfruten su estancia.</p> <!-- Párrafo de texto -->
</section> <!-- Cierre del section intro -->
<?php include '../barrasNavegacion\footer.php'; ?> <!-- Footer importado externamente mediante PHP -->
</body> <!-- Cierre del body -->
</html> <!-- Cierre del HTML -->