<?php
// Script de diagnóstico para verificar la base de datos y categorías

include 'conexion.php';
include 'clases/Categoria.php';

$database = new Database();
$conn = $database->conectar();

echo "<h1>Diagnóstico del Sistema de Categorías</h1>";

// 1. Verificar categorías madre
echo "<h2>1. Categorías Madre (id_madre = NULL)</h2>";
$sql_madre = "SELECT id_categoria, titulo, id_madre FROM CATEGORIA WHERE id_madre IS NULL ORDER BY id_categoria";
$stmt = $conn->prepare($sql_madre);
$stmt->execute();
$madres = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Título</th><th>ID Madre</th></tr>";
foreach ($madres as $madre) {
    echo "<tr>";
    echo "<td>" . $madre['id_categoria'] . "</td>";
    echo "<td>" . $madre['titulo'] . "</td>";
    echo "<td>" . ($madre['id_madre'] === null ? "NULL" : $madre['id_madre']) . "</td>";
    echo "</tr>";
}
echo "</table>";

// 2. Verificar subcategorías para cada madre
echo "<h2>2. Subcategorías para cada Categoría Madre</h2>";

foreach ($madres as $madre) {
    $id_madre = $madre['id_categoria'];
    echo "<h3>" . $madre['titulo'] . " (ID: " . $id_madre . ")</h3>";
    
    $sql_sub = "SELECT id_categoria, titulo, id_madre FROM CATEGORIA WHERE id_madre = :id_madre ORDER BY id_categoria";
    $stmt_sub = $conn->prepare($sql_sub);
    $stmt_sub->execute([':id_madre' => (int)$id_madre]);
    $subcategorias = $stmt_sub->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($subcategorias)) {
        echo "<p><em>No hay subcategorías</em></p>";
    } else {
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>ID</th><th>Título</th><th>ID Madre</th></tr>";
        foreach ($subcategorias as $sub) {
            echo "<tr>";
            echo "<td>" . $sub['id_categoria'] . "</td>";
            echo "<td>" . $sub['titulo'] . "</td>";
            echo "<td>" . $sub['id_madre'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}

// 3. Verificar usando la clase Categoria
echo "<h2>3. Prueba con la Clase Categoria</h2>";

$categorias = Categoria::obtenerCategoriasMadre($conn);
echo "<p>Categorías madre obtenidas: " . count($categorias) . "</p>";

foreach ($categorias as $cat) {
    echo "<h4>" . $cat->getTitulo() . " (ID: " . $cat->getIdCategoria() . ")</h4>";
    $subcats = Categoria::obtenerSubcategorias($conn, $cat->getIdCategoria());
    echo "<p>Subcategorías: " . count($subcats) . "</p>";
    
    if (!empty($subcats)) {
        echo "<ul>";
        foreach ($subcats as $subcat) {
            echo "<li>" . $subcat->getTitulo() . " (ID: " . $subcat->getIdCategoria() . ", Madre: " . $subcat->getIdMadre() . ")</li>";
        }
        echo "</ul>";
    }
}
?>
