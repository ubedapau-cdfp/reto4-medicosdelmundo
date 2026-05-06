<?php
include 'conexion.php';
include 'clases/Categoria.php';
include 'clases/Bloque.php';
include 'clases/ContenidoExterno.php';

$database = new Database();
$conn = $database->conectar();

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

// Verificar si se pasa el id de la categoría madre
if (!isset($_GET['id'])) {
    echo "Por favor pasa ?id=<numero>";
    exit;
}

$id_madre = (int)$_GET['id'];
$categoria_madre = Categoria::obtenerPorId($conn, $id_madre);

if (!$categoria_madre) {
    echo "Categoría madre no encontrada.";
    exit;
}

echo "<h1>Debug: " . htmlspecialchars($categoria_madre->getTitulo()) . "</h1>";

$subcategorias = Categoria::obtenerSubcategorias($conn, $id_madre);

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Título</th><th>Slug</th><th>Imagen en Filesystem</th><th>Imagen en BD (fallback)</th><th>Imagen Final</th></tr>";

$extensiones = ['.jpg', '.jpeg', '.png', '.gif', '.webp', '.svg', '.jfif'];
$carpeta_imagenes = 'Imagenes/Contenidos/';

foreach ($subcategorias as $sub) {
    $slug = textoASlug($sub->getTitulo());

    // Buscar en filesystem
    $imagen_fs = '';
    foreach ($extensiones as $ext) {
        $ruta = $carpeta_imagenes . $slug . $ext;
        if (file_exists($ruta)) {
            $imagen_fs = $ruta;
            break;
        }
    }

    // Buscar en BD (fallback)
    $imagen_bd = '';
    $bloques = Bloque::obtenerPorCategoriaId($conn, $sub->getIdCategoria());
    if (!empty($bloques)) {
        foreach ($bloques as $bloque) {
            $imagenes = ContenidoExterno::obtenerImagenesPorBloqueId($conn, $bloque->getIdBloque());
            if (!empty($imagenes)) {
                $imagen_bd = $imagenes[0];
                break;
            }
        }
    }

    // Imagen final (prioridad: filesystem > BD)
    $imagen_final = $imagen_fs ?: $imagen_bd;

    echo "<tr>";
    echo "<td>" . $sub->getIdCategoria() . "</td>";
    echo "<td>" . htmlspecialchars($sub->getTitulo()) . "</td>";
    echo "<td>" . htmlspecialchars($slug) . "</td>";
    echo "<td>" . ($imagen_fs ? "✓ " . htmlspecialchars($imagen_fs) : "✗ NO") . "</td>";
    echo "<td>" . ($imagen_bd ? "✓ " . htmlspecialchars($imagen_bd) : "✗ NO") . "</td>";
    echo "<td><strong>" . ($imagen_final ? htmlspecialchars($imagen_final) : "✗ NO ENCONTRADA") . "</strong></td>";
    echo "</tr>";
}

echo "</table>";

echo "<h2>Instrucciones para probar:</h2>";
echo "<p>Abre contenidos.php?id=" . $id_madre . " en otra pestaña y compara las imágenes mostradas con la columna 'Imagen Final' de esta tabla.</p>";

?>
