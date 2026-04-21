<?php
session_start();
// Dirección base del proyecto
$base = '/reto4-medicosdelmundo/';
// PASO 2: Si el usuario no es administrador (rol = 3), mandarlo al login
if (!isset($_SESSION['usuario_id']) || $_SESSION['id_rol'] != 3) {
    header('Location: ' . $base . 'signin.php');
    exit();
}
 
// PASO 3: Conectar con la base de datos
include_once __DIR__ . "/../conexion.php";
// Inicializar Database y obtener PDO en $conn (compatibilidad)
$database = new Database();
$conn = $database->conectar();
 
// Añadir la columna "ruta" a la tabla si todavía no existe
try {
    $conn->exec("ALTER TABLE CATEGORIA ADD COLUMN IF NOT EXISTS ruta VARCHAR(255);");
} catch (PDOException $e) {
    // Si ya existe la columna, ignoramos el error y continuamos
}
 
// Variables para mostrar mensajes en pantalla
$error   = '';
$mensaje = '';
 
// ─────────────────────────────────────────────
// PASO 4: Si se envía el formulario para AÑADIR una categoría...
// ─────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'add') {
 
    // Leer los datos del formulario (trim quita espacios al inicio y al final)
    $titulo      = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $icono       = trim($_POST['icono']);
    $ruta        = trim($_POST['ruta']);
    $id_madre    = ($_POST['id_madre'] != '') ? intval($_POST['id_madre']) : 'NULL';
 
    // Comprobaciones básicas antes de guardar
    if ($titulo == '') {
        $error = 'El título es obligatorio.';
 
    } elseif ($id_madre != 'NULL' && $ruta == '') {
        $error = 'Si añades una subcategoría debes indicar una ruta.';
 
    } elseif (strpos($ruta, '..') !== false) {
        // Evitar rutas peligrosas como "../../archivo"
        $error = 'La ruta no puede contener "..".';
 
    } else {
        // Limpiar la ruta: quitar barras invertidas y la barra inicial
        $ruta_limpia = ltrim(str_replace('\\', '/', $ruta), '/');
        if ($ruta_limpia == '') $ruta_limpia = 'NULL';
 
        // Fecha de hoy en formato YYYY-MM-DD
        $fecha = date('Y-m-d');
 
        // Construir y ejecutar la consulta SQL directamente
        try {
            $conn->exec("INSERT INTO CATEGORIA (titulo, descripcion, icono, id_madre, fecha_actualizacion, ruta)
                         VALUES ('$titulo', '$descripcion', '$icono', $id_madre, '$fecha', '$ruta_limpia')");
 
            // ── Crear el archivo PHP en disco si se dio una ruta ──
            if ($ruta_limpia != 'NULL') {
 
                // Ruta absoluta de la carpeta raíz del proyecto
                $carpeta_proyecto = realpath(__DIR__ . '/../');
                $ruta_archivo     = $carpeta_proyecto . '/' . $ruta_limpia;
 
                // Añadir .php al final si el archivo no tiene extensión
                if (pathinfo($ruta_archivo, PATHINFO_EXTENSION) == '') {
                    $ruta_archivo .= '.php';
                }
 
                // Crear las carpetas necesarias si no existen
                $carpeta = dirname($ruta_archivo);
                if (!is_dir($carpeta)) {
                    mkdir($carpeta, 0755, true); // true = crear carpetas dentro de carpetas
                }
 
                // Escribir el archivo solo si todavía no existe
                if (!file_exists($ruta_archivo)) {
                    $contenido  = "<?php include_once \$_SERVER['DOCUMENT_ROOT'] . '{$base}barrasNavegacion/headerDINAMICO.php'; ?>\n";
                    $contenido .= "<!doctype html>\n<html lang='es'>\n<head>\n";
                    $contenido .= "<meta charset='utf-8'>\n";
                    $contenido .= "<title>$titulo</title>\n";
                    $contenido .= "</head>\n<body>\n";
                    $contenido .= "<h1>$titulo</h1>\n";
                    $contenido .= "<p>Contenido creado automáticamente.</p>\n";
                    $contenido .= "</body>\n</html>";
 
                    file_put_contents($ruta_archivo, $contenido); // Escribir el archivo en disco
                }
            }
 
            $mensaje = 'Categoría añadida correctamente.';
 
        } catch (PDOException $e) {
            $error = 'Error al guardar: ' . $e->getMessage();
        }
    }
}
 

// PASO 5: Si se pide ELIMINAR una categoría...
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
 
    $id = intval($_GET['id']); // Convertir a número entero por seguridad
 
    try {
        // Borrar la categoría y todas sus subcategorías de golpe
        $conn->exec("DELETE FROM CATEGORIA WHERE id_categoria = $id OR id_madre = $id");
 
        // Volver a la página de gestión tras borrar
        header('Location: ' . $base . 'Vistadmin/gestion_header.php');
        exit();
 
    } catch (PDOException $e) {
        $error = 'Error al eliminar: ' . $e->getMessage();
    }
}
 
// ─────────────────────────────────────────────
// PASO 6: Leer todas las categorías para mostrarlas en pantalla
// ─────────────────────────────────────────────
try {
    // Primero las categorías principales (sin madre), luego las subcategorías
    $stmt       = $conn->query("SELECT * FROM CATEGORIA ORDER BY id_madre NULLS FIRST, id_categoria ASC");
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC); // Guardar los resultados en un array
 
} catch (PDOException $e) {
    $categorias = [];
    $error      = 'Error al leer categorías: ' . $e->getMessage();
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Gestión del Header</title>
    <link rel="stylesheet" href="<?= $base ?>estilos.css">
</head>
<body>
    <header class="gestion-header-container">
        <a href="<?= $base ?>home/home.php">Volver al sitio</a>
    </header>

    <main class="gestion-header-main">
        <h1>Gestión del Header (muy simple)</h1>
        <?php if ($error): ?>
            <p class="gestion-error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($message): ?>
            <p class="gestion-message"><?php echo $message; ?></p>
        <?php endif; ?>

        <section class="gestion-section">
            <h2>Añadir sección / subapartado</h2>
            <form method="post">
                <input type="hidden" name="action" value="add">
                <p>
                    <label>Título:<br>
                    <input type="text" name="titulo" required class="gestion-input-300"></label>
                </p>
                <p>
                    <label>Descripción:<br>
                    <input type="text" name="descripcion" class="gestion-input-300"></label>
                </p>
                <p>
                    <label>Icono (clase font-awesome):<br>
                    <input type="text" name="icono" placeholder="fa-users" class="gestion-input-300"></label>
                </p>
                <p>
                    <label>Madre (dejar vacío si es sección principal):<br>
                    <select name="id_madre">
                        <option value="">-- Ninguna --</option>
                        <?php foreach ($categorias as $c): if ($c['id_madre'] !== null) continue; ?>
                            <option value="<?= $c['id_categoria'] ?>"><?= htmlspecialchars($c['titulo']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    </label>
                </p>
                <p>
                    <label>Ruta (ej: contratosficticios/contratosretratos) — requerida para subapartados:<br>
                    <input type="text" name="ruta" class="gestion-input-400"></label>
                </p>
                <p>
                    <button type="submit">Añadir</button>
                </p>
            </form>
        </section>

        <section>
            <h2>Secciones actuales</h2>
            <ul>
                <?php
                // Mostrar jerarquía simple
                foreach ($categorias as $cat) {
                    if ($cat['id_madre'] !== null) continue; // saltar hijas aquí
                    echo '<li><strong>' . htmlspecialchars($cat['titulo']) . '</strong> ';
                    if (!empty($cat['ruta'])) echo '(<em>' . htmlspecialchars($cat['ruta']) . '</em>)';
                    echo ' <a href="?action=delete&id=' . intval($cat['id_categoria']) . '" class="gestion-delete-link">Eliminar</a>';
                    // mostrar hijas
                    echo '<ul>';
                    foreach ($categorias as $sub) {
                        if ($sub['id_madre'] == $cat['id_categoria']) {
                            echo '<li>' . htmlspecialchars($sub['titulo']);
                            if (!empty($sub['ruta'])) echo ' (<em>' . htmlspecialchars($sub['ruta']) . '</em>)';
                            echo ' <a href="?action=delete&id=' . intval($sub['id_categoria']) . '" class="gestion-delete-link">Eliminar</a>';
                            echo '</li>';
                        }
                    }
                    echo '</ul>';
                    echo '</li>';
                }
                ?>
            </ul>
        </section>
    </main>
</body>
</html>
