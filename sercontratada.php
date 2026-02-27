<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ser Contratada</title>
<link rel="stylesheet" href="prueba.css">
</head>
<body>
<?php include 'barrasNavegacion\header.php'; ?>
<p>
<section class="contenidos">
    <p><b>Ser Contratada</b></p>
    <p>Escoge, del siguiente selector, el apartado que quieres leer:</p>
    <form action="" method='POST'>
        <select class="opciones">
            <option value="edad" name="edad">Edad</option>
            <option value="nacionalidad" name="nacionalidad">Nacionalidad</option>
            <option value="relaciones" name="relaciones">Relaciones Excluidas y Especiales</option>
        </select>
        <div><input type="button" action="submit" value="Mostrar apartado"></div>
    </form>
</section>

<section class="subapartados">
<?php 
if (isset($_POST['opciones'])){
    $opciones=$_POST['opciones'];
    if ($opciones=="edad"){

    }
}
?>
</section>


<?php include 'barrasNavegacion\footer.php'; ?>


</body>
</html>