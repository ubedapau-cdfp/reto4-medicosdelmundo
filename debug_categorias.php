<?php
// Script de debug para verificar qué subcategorías se obtienen
include 'conexion.php';
include 'clases/Categoria.php';

$database = new Database();
$conn = $database->conectar();

// Categorías madre
$categorias_madre = Categoria::obtenerCategoriasMadre($conn);

echo "<pre>";
echo "=== CATEGORÍAS MADRE ===\n";
foreach ($categorias_madre as $madre) {
    echo "\nCategoría: " . $madre->getTitulo() . " (ID: " . $madre->getIdCategoria() . ")\n";
    echo "ID Madre: " . ($madre->getIdMadre() === null ? "NULL (es madre)" : $madre->getIdMadre()) . "\n";
    
    $subcategorias = Categoria::obtenerSubcategorias($conn, $madre->getIdCategoria());
    echo "Subcategorías obtenidas: " . count($subcategorias) . "\n";
    
    foreach ($subcategorias as $sub) {
        echo "  - " . $sub->getTitulo() . " (ID: " . $sub->getIdCategoria() . ", ID Madre: " . $sub->getIdMadre() . ")\n";
    }
}
echo "</pre>";
?>
