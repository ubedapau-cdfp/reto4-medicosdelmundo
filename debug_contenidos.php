<?php
include 'conexion.php';
include 'clases/Categoria.php';

$database = new Database();
$conn = $database->conectar();

if (!isset($_GET['id'])) {
    echo "Falta el parámetro ?id=";
    exit;
}

$id_categoria = (int)$_GET['id'];
$categoria = Categoria::obtenerPorId($conn, $id_categoria);

if (!$categoria) {
    echo "Categoría no encontrada.";
    exit;
}

$isMadre = ($categoria->getIdMadre() === null);
$subcategorias = [];
if ($isMadre) {
    $subcategorias = Categoria::obtenerSubcategorias($conn, $id_categoria);
}

echo "<h1>Debug de contenidos.php para ID: " . $id_categoria . "</h1>";
echo "<pre>";
echo "Categoría encontrada: " . htmlspecialchars($categoria->getTitulo()) . "\n";
echo "ID Madre: " . ($categoria->getIdMadre() ?? "NULL") . "\n";
echo "¿Es Madre?: " . ($isMadre ? "SÍ (true)" : "NO (false)") . "\n";
echo "Número de subcategorías: " . count($subcategorias) . "\n";
echo "\nSubcategorías:\n";
foreach ($subcategorias as $sub) {
    echo "  - " . htmlspecialchars($sub->getTitulo()) . " (ID: " . $sub->getIdCategoria() . ")\n";
}
echo "</pre>";

if (!$isMadre) {
    echo "<p style='color: red;'><strong>⚠️ Esta categoría NO es madre, por eso no muestra subcategorías.</strong></p>";
} elseif (empty($subcategorias)) {
    echo "<p style='color: red;'><strong>⚠️ Esta es una categoría madre pero NO tiene subcategorías.</strong></p>";
} else {
    echo "<p style='color: green;'><strong>✓ Esta categoría madre tiene " . count($subcategorias) . " subcategoría(s).</strong></p>";
}
?>
