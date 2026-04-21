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
    <h1>Bienvenidos a la página</h1>
    <p>Disfruten su estancia.</p>
</section>
<?php include '../barrasNavegacion/footer.php'; ?>
</body>
</html>