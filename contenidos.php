<?php
// Incluimos la conexión y las clases
include 'conexion.php';
include 'clases/Categoria.php';
include 'clases/Bloque.php';

// Inicializamos la conexión POO
$database = new Database();
$conn = $database->conectar();

// Verificar si se pasa el id de la categoría
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Categoría no especificada o inválida.";
    exit;
}

$id_categoria = (int)$_GET['id'];

// Obtener la categoría por id
$categoria = Categoria::obtenerPorId($conn, $id_categoria);
if (!$categoria) {
    echo "Categoría no encontrada.";
    exit;
}

// Obtener los bloques de esta categoría
$listaBloques = Bloque::obtenerPorCategoriaId($conn, $id_categoria);

// Verificar si es una categoría madre (sin id_madre)
$isMadre = ($categoria->getIdMadre() === null);

// Si es madre, obtener subcategorías
$subcategorias = [];
if ($isMadre) {
    $subcategorias = Categoria::obtenerSubcategorias($conn, $id_categoria);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($categoria->getTitulo()); ?></title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<?php include 'barrasNavegacion/header.php'; ?>

<section class="contenidos">
    <h1><?php echo htmlspecialchars($categoria->getTitulo()); ?></h1>
    <p><?php echo htmlspecialchars($categoria->getDescripcion()); ?></p>
</section>

<section class="subapartados">
<?php
if ($isMadre) {
    // Mostrar subcategorías como enlaces
    if (!empty($subcategorias)) {
        echo "<h2>Subapartados:</h2><ul>";
        foreach ($subcategorias as $sub) {
            echo "<li><a href='contenidos.php?id=" . $sub->getIdCategoria() . "'>" . htmlspecialchars($sub->getTitulo()) . "</a></li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No hay subapartados disponibles.</p>";
    }
} else {
    // Mostrar bloques de contenido
    if (!empty($listaBloques)) {
        foreach ($listaBloques as $bloque) {
            $bloque->mostrarDatos();
        }
    } else {
        echo "<p>No hay contenido disponible para esta categoría.</p>";
    }
}
?>
</section>

<?php include 'barrasNavegacion/footer.php'; ?>

</body>
</html>