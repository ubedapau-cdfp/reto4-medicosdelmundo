<?php 
    // Incluimos la conexión y las clases (Estilo W3Schools)
    include 'conexion.php'; 
    include 'clases/Categoria.php';
    include 'clases/Bloque.php';

    // Paso 1: Obtener las categorías ANTES de mostrar el HTML del selector
    $listaCategorias = Categoria::obtenerTodas($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contratada</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<?php include 'barrasNavegacion\header.php'; ?>

<section class="contenidos">
    <p><b>Ser Contratada</b></p>
    <p>Escoge un apartado:</p>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <select class="opciones" name="opciones">
            <?php 
            // W3Schools suele usar foreach para listas dinámicas
            foreach ($listaCategorias as $cat) {
                echo '<option value="' . $cat->getTitulo() . '">' . $cat->getTitulo() . '</option>';
            }
            ?>
        </select>
        <button type="submit">Mostrar apartado</button>
    </form>
</section>

<!-- <section class="contenidos">
    <p><b>Ser Contratada</b></p>
    <p>Escoge, del siguiente selector, el apartado que quieres leer:</p>
    <form action="" method='POST'>
        <select class="opciones" name="opciones">
            <option value="Edad">Edad</option>
            <option value="Nacionalidad">Nacionalidad</option>
            <option value="Relaciones Excluidas y Especiales">Relaciones Excluidas y Especiales</option>
        </select>
        <button type="Submit" value="Submit">Mostrar apartado</button>
    </form>
</section> -->

<section class="subapartados">
<?php 
    // Paso 2: Procesar el formulario cuando se envía (POST)
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['opciones'])) {
        $nombreCat = $_POST['opciones'];

        // Llamamos al método estático que creamos
        $listaBloques = Bloque::obtenerPorCategoria($conn, $nombreCat);

        if (!empty($listaBloques)) {
            foreach($listaBloques as $bloque) {
                $bloque->mostrarDatos();
            }
        } else {
            echo "No se encontraron resultados.";
        }
    }
?>
</section>

<?php include 'barrasNavegacion\footer.php'; ?>


</body>
</html>