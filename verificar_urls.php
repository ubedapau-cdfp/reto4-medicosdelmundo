<?php
// Script para verificar las URLs que se generan en el menú

include 'conexion.php';
include 'clases/Categoria.php';

$database = new Database();
$conn = $database->conectar();
$base = '/reto4-medicosdelmundo/';

$categorias_madre = Categoria::obtenerCategoriasMadre($conn);

echo "<h1>URLs Generadas en el Menú</h1>";
echo "<h2>Categorías Madre</h2>";

foreach ($categorias_madre as $madre) {
    $madreHref = $base . "contenidos.php?id=" . $madre->getIdCategoria();
    echo "<p><strong>" . $madre->getTitulo() . ":</strong> <code>" . $madreHref . "</code></p>";
    
    $subcategorias = Categoria::obtenerSubcategorias($conn, $madre->getIdCategoria());
    
    if (!empty($subcategorias)) {
        echo "<ul>";
        foreach ($subcategorias as $hija) {
            $hijaHref = $base . "contenidos.php?id=" . $hija->getIdCategoria();
            echo "<li><strong>" . $hija->getTitulo() . ":</strong> <code>" . $hijaHref . "</code></li>";
        }
        echo "</ul>";
    }
}
?>
