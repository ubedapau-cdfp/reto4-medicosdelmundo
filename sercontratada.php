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
        <select class="opciones" name="opciones">
            <option value="edad" name="edad">Edad</option>
            <option value="nacionalidad" name="nacionalidad">Nacionalidad</option>
            <option value="relaciones" name="relaciones">Relaciones Excluidas y Especiales</option>
        </select>
        <div><button type="Submit" value="Submit">Mostrar apartado</button></div>
    </form>
</section>

<section class="subapartados">

<?php 
if (isset($_POST['opciones'])){
    $opciones=$_POST['opciones'];
    if ($opciones=="edad"){
        echo "<h2>Edad</h2>";
        echo "Este apartado tendrá su respectivo contenido en un futuro. Perdón por las molestias.";
    } elseif ($opciones=="nacionalidad"){
        echo "<h2>Nacionalidad</h2>";
        echo "Este apartado tendrá su respectivo contenido en un futuro. Perdón por las molestias.";
    } elseif ($opciones=="relaciones"){
        echo "<h2>Relaciones Excluidas y Especiales</h2>";
        echo "Este apartado tendrá su respectivo contenido en un futuro. Perdón por las molestias.";
    } else {
        echo "<h1>Error en la selección.</h1>";
    }
}
?>
</section>


<?php include 'barrasNavegacion\footer.php'; ?>


</body>
</html>