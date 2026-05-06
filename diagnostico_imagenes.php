<?php
include 'conexion.php';
include 'clases/Categoria.php';

$database = new Database();
$conn = $database->conectar();

// Función helper para convertir un texto a slug
function textoASlug($texto) {
    $slug = strtolower($texto);
    $slug = preg_replace('/[áàäâã]/i', 'a', $slug);
    $slug = preg_replace('/[éèëê]/i', 'e', $slug);
    $slug = preg_replace('/[íìïî]/i', 'i', $slug);
    $slug = preg_replace('/[óòöô]/i', 'o', $slug);
    $slug = preg_replace('/[úùüû]/i', 'u', $slug);
    $slug = preg_replace('/[ñ]/i', 'n', $slug);
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}

// Extensiones de imagen permitidas
$extensiones = ['.jpg', '.jpeg', '.png', '.gif', '.webp', '.svg', '.jfif'];
$carpeta_imagenes = 'Imagenes/Contenidos/';

// Obtener todas las categorías
$categorias = Categoria::obtenerTodas($conn);

echo "<h1>Diagnóstico de Imágenes de Categorías</h1>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Título</th><th>Slug Esperado</th><th>Imagen Existe</th><th>Ruta Imagen</th></tr>";

foreach ($categorias as $cat) {
    $slug = textoASlug($cat->getTitulo());
    $imagen_encontrada = false;
    $ruta_imagen = '';

    foreach ($extensiones as $ext) {
        $ruta = $carpeta_imagenes . $slug . $ext;
        if (file_exists($ruta)) {
            $imagen_encontrada = true;
            $ruta_imagen = $ruta;
            break;
        }
    }

    $existe = $imagen_encontrada ? '✓ SÍ' : '✗ NO';
    $color = $imagen_encontrada ? 'green' : 'red';

    echo "<tr>";
    echo "<td>" . $cat->getIdCategoria() . "</td>";
    echo "<td>" . htmlspecialchars($cat->getTitulo()) . "</td>";
    echo "<td>" . htmlspecialchars($slug) . "</td>";
    echo "<td style='color: $color; font-weight: bold;'>" . $existe . "</td>";
    echo "<td>" . htmlspecialchars($ruta_imagen) . "</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h2>Archivos de Imagen Disponibles</h2>";
echo "<ul>";
$archivos = scandir($carpeta_imagenes);
foreach ($archivos as $archivo) {
    if ($archivo !== '.' && $archivo !== '..' && !is_dir($carpeta_imagenes . $archivo)) {
        echo "<li>$archivo</li>";
    }
}
echo "</ul>";

?>
