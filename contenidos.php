<?php
// Incluimos la conexión y las clases
include 'conexion.php';
include 'clases/Categoria.php';
include 'clases/Bloque.php';
include 'clases/ContenidoExterno.php';

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
    <link rel="icon" type="image/png" href="Imagenes/Logoreal.png">
</head>
<body>

<?php
// Función helper para convertir un texto a slug (ej: "Ser Trabajadora" → "ser-trabajadora")
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

// Función helper para obtener la imagen de una categoría por su nombre
function obtenerImagenCategoria($conn, $id_categoria, $titulo_categoria = null) {
    // Si no se proporciona el título, obtenerlo de la BD
    if (!$titulo_categoria) {
        $categoria = Categoria::obtenerPorId($conn, $id_categoria);
        if (!$categoria) {
            return null;
        }
        $titulo_categoria = $categoria->getTitulo();
    }
    
    // Convertir el título a slug para buscar la imagen
    $slug = textoASlug($titulo_categoria);
    
    // Extensiones de imagen permitidas
    $extensiones = ['.jpg', '.jpeg', '.png', '.gif', '.webp', '.svg', '.jfif'];
    $carpeta_imagenes = 'Imagenes/Contenidos/';
    
    // Buscar si existe una imagen con ese nombre
    foreach ($extensiones as $ext) {
        $ruta_imagen = $carpeta_imagenes . $slug . $ext;
        if (file_exists($ruta_imagen)) {
            return $ruta_imagen;
        }
    }
    
    // Si no se encuentra imagen por slug, buscar en los bloques (fallback)
    $bloques = Bloque::obtenerPorCategoriaId($conn, $id_categoria);
    if (!empty($bloques)) {
        foreach ($bloques as $bloque) {
            $imagenes = ContenidoExterno::obtenerImagenesPorBloqueId($conn, $bloque->getIdBloque());
            if (!empty($imagenes)) {
                return $imagenes[0]; // Retorna la primera imagen encontrada
            }
        }
    }
    return null;
}

$imagenCategoria = obtenerImagenCategoria($conn, $id_categoria, $categoria->getTitulo());
?>

<?php include 'barrasNavegacion/header.php'; ?>

<section class="contenidos contenido-con-imagen">
    <div class="contenido-texto">
        <h1><?php echo htmlspecialchars($categoria->getTitulo()); ?></h1>
        <p><?php echo htmlspecialchars($categoria->getDescripcion()); ?></p>
    </div>
    <?php if ($imagenCategoria): ?>
        <div class="contenido-imagen">
            <img src="<?php echo htmlspecialchars($imagenCategoria); ?>" alt="Imagen de <?php echo htmlspecialchars($categoria->getTitulo()); ?>">
        </div>
    <?php endif; ?>
</section>

<section class="subapartados">
<?php
if ($isMadre) {
    // Mostrar subcategorías como enlaces
    if (!empty($subcategorias)) {
        echo "<h2>Subapartados:</h2><div class='subapartados-grid'>";
        foreach ($subcategorias as $sub) {
            $imagenSub = obtenerImagenCategoria($conn, $sub->getIdCategoria(), $sub->getTitulo());
            echo "<a href='contenidos.php?id=" . $sub->getIdCategoria() . "' class='subapartado-card'>";
            if (!empty($imagenSub)) {
                echo "<div class='subapartado-thumb'><img src='" . htmlspecialchars($imagenSub) . "' alt='" . htmlspecialchars($sub->getTitulo()) . "'></div>";
            }
            echo "<div class='subapartado-info'><h3>" . htmlspecialchars($sub->getTitulo()) . "</h3><p>" . htmlspecialchars($sub->getDescripcion()) . "</p></div>";
            echo "</a>";
        }
        echo "</div>";
    } else {
        echo "<p>No hay subapartados disponibles.</p>";
    }
} else {
    // Mostrar bloques de contenido
    if (!empty($listaBloques)) {
        foreach ($listaBloques as $bloque) {
            $bloque->mostrarDatos();
            
            // Obtener y mostrar enlaces externos si existen
            $enlacesExternos = ContenidoExterno::obtenerEnlacesPorBloqueId($conn, $bloque->getIdBloque());
            if (!empty($enlacesExternos)) {
                echo "<div class='enlaces-externos'>";
                echo "<h4>Enlaces relacionados:</h4>";
                echo "<ul>";
                foreach ($enlacesExternos as $enlace) {
                    echo "<li><a href='" . htmlspecialchars($enlace->getUrlExternas()) . "' target='_blank'>" . htmlspecialchars($enlace->getUrlExternas()) . "</a></li>";
                }
                echo "</ul>";
                echo "</div>";
            }
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