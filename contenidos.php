<?php
// Incluimos la conexión y las clases
include 'conexion.php';
include 'clases/Categoria.php';
include 'clases/Bloque.php';
include 'clases/ContenidoExterno.php';

// Forzar que no se use caché
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

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

// --- FUNCIONES HELPER (Lógica de negocio) ---

/**
 * Función Helper: Genera el nombre base del archivo (slug)
 * Esto convierte "Maternidad y Pausas" en "maternidad-y-pausas"
 */
function textoASlug($texto) {
    $slug = mb_strtolower($texto, 'UTF-8');
    $slug = preg_replace('/[áàäâã]/u', 'a', $slug);
    $slug = preg_replace('/[éèëê]/u', 'e', $slug);
    $slug = preg_replace('/[íìïî]/u', 'i', $slug);
    $slug = preg_replace('/[óòöô]/u', 'o', $slug);
    $slug = preg_replace('/[úùüû]/u', 'u', $slug);
    $slug = preg_replace('/[ñ]/u', 'n', $slug);
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    return trim($slug, '-');
}

/**
 * Función mejorada para encontrar la imagen real
 */
function obtenerRutaImagen($titulo, $carpeta) {
    $baseNombre = textoASlug($titulo);
    // Añadimos .jfif a la lista ya que lo usas en Tipos de Contrato según tu SQL
    $extensiones = ['.jpg', '.jpeg', '.png', '.webp', '.jfif'];
    
    foreach ($extensiones as $ext) {
        $ruta = $carpeta . $baseNombre . $ext;
        if (file_exists($ruta)) {
            return $ruta;
        }
    }
    return "Imagenes/Categorias/default.jpg";
}

// Imagen principal del banner
$imagenPrincipal = obtenerRutaImagen($categoria->getTitulo(), 'Imagenes/Contenidos/');
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

<?php include 'barrasNavegacion/header.php'; ?>

<section class="contenidos contenido-con-imagen">
    <div class="contenido-texto">
        <h1><?php echo htmlspecialchars($categoria->getTitulo()); ?></h1>
        <p><?php echo htmlspecialchars($categoria->getDescripcion()); ?></p>
    </div>
    
    <?php if (file_exists($imagenPrincipal) && !strpos($imagenPrincipal, 'default.jpg')): ?>
    <div class="contenido-imagen">
        <img src="<?php echo htmlspecialchars($imagenPrincipal); ?>" alt="<?php echo htmlspecialchars($categoria->getTitulo()); ?>">
    </div>
    <?php endif; ?>
</section>

<section class="subapartados">
    <?php if ($isMadre): ?>
        <?php 
        // Lógica para categorías Madre (Muestra cuadrícula de subcategorías)
        $subcategorias = Categoria::obtenerHijas($conn, $id_categoria); 
        if (!empty($subcategorias)): 
        ?>
            <h2>Subapartados:</h2>
            <div class="subapartados-grid"> 
                <?php foreach ($subcategorias as $sub): 
                    // Usamos tu nueva función para obtener la ruta de la imagen
                    $imgSub = obtenerRutaImagen($sub->getTitulo(), 'Imagenes/Contenidos/');
                ?>
                    <a href="contenidos.php?id=<?php echo $sub->getIdCategoria(); ?>" class="subapartado-card">
                        <div class="subapartado-thumb">
                            <img src="<?php echo htmlspecialchars($imgSub); ?>" alt="<?php echo htmlspecialchars($sub->getTitulo()); ?>">
                        </div>
                        <div class="subapartado-info">
                            <h3><?php echo htmlspecialchars($sub->getTitulo()); ?></h3>
                            <p><?php echo htmlspecialchars($sub->getDescripcion()); ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No hay subapartados disponibles.</p>
        <?php endif; ?>

    <?php else: ?>
        <?php 
        // Lógica para categorías finales (Muestra los bloques de contenido)
        if (!empty($listaBloques)): 
            foreach ($listaBloques as $bloque): 
                // --- FILTRO PARA OCULTAR LA LEYENDA DE LA PORTADA ---
                // Si el título es "Portada", ignoramos este bloque y saltamos al siguiente
                if (trim($bloque->getSubtitulo()) === 'Portada') {
                    continue; 
                }
                if (trim($bloque->getContenido()) === 'Imagen de portada de la subcategoría.') {
                    continue;
                }
                // Mostramos los datos del bloque (Texto, etc.)
                $bloque->mostrarDatos(); 
                
                // Recuperamos y mostramos los enlaces externos
                $enlacesExternos = ContenidoExterno::obtenerEnlacesPorBloqueId($conn, $bloque->getIdBloque());
                if (!empty($enlacesExternos)): 
                ?>
                    <div class='enlaces-externos'>
                        <h4>Enlaces relacionados:</h4>
                        <ul>
                            <?php foreach ($enlacesExternos as $enlace): ?>
                                <li>
                                    <a href="<?php echo htmlspecialchars($enlace->getUrlExternas()); ?>" target="_blank">
                                        <?php echo htmlspecialchars($enlace->getUrlExternas()); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php 
                endif; 
            endforeach; 
        else: ?>
            <p>No hay contenido disponible para esta categoría.</p>
        <?php endif; ?>
    <?php endif; ?>
</section>

<?php include 'barrasNavegacion/footer.php'; ?>

</body>
</html>