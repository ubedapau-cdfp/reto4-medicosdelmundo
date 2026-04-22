<?php
session_start();
$base = '/reto4-medicosdelmundo/';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inicio</title>
<link rel="icon" type="image/png" href="<?= $base ?>Imagenes/Logoreal.png">
<link rel="stylesheet" href="../estilos.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<?php
if (!isset($_SESSION['id_rol']) || intval($_SESSION['id_rol']) === 1) {
    include '../barrasNavegacion/header.php';
} elseif (in_array(intval($_SESSION['id_rol']), [2, 3], true)) {
    include '../barrasNavegacion/headeradmin.php';
} else {
    include '../barrasNavegacion/header.php';
}
?>
<section class="intro">
    &nbsp; <!-- ESPACIO EN BLANCO -->
    <p class="texto-inicio">Bienvenidas a la página web de Médicos del Mundo</p> 
    <p class="subtexto-inicio">Esperemos que disfruten su estancia.</p>
    <section class="imagenes">
        <img src="../Imagenes/imageninicio1.jpg" alt="Imagen 1" width="600" height="400">
        <img src="../Imagenes/imageninicio2.jpg" alt="Imagen 1" width="600" height="400">
    </section>
    <section class="imagenes2">
        <img src="../Imagenes/imageninicio3.jpg" alt="Imagen 1" width="600" height="400">
        <img src="../Imagenes/imageninicio4.jpg" alt="Imagen 1" width="600" height="400">
</section>
<?php include '../barrasNavegacion/footer.php'; ?>
</body>
</html>