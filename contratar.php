<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ser Contratada</title>
<link rel="stylesheet" href="estilos.css">
</head>
<body>
<?php include 'barrasNavegacion\header.php'; ?>
<p>
<section class="contenidos">
    <p><b>Contratos</b></p>
    <p>Escoge, del siguiente selector, el apartado que quieres leer:</p>
    <form action="" method='POST'>
        <select class="opciones" name="opciones">
            <option value="7">Edad</option>
            <option value="8">Nacionalidad</option>
            <option value="9">Relaciones Excluidas y Especiales</option>
        </select>
        <button type="Submit" value="Submit">Mostrar apartado</button>
    </form>
</section>

<section class="subapartados">
</section>
<?php include 'barrasNavegacion\footer.php'; ?>

<?php 
//prueba.php
?>

</body>
</html>
