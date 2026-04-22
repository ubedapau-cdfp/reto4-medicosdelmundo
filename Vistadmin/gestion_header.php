<?php
session_start();
// Arrancamos la "memoria" de la sesión: esto permite recordar
// si el usuario ya ha iniciado sesión y quién es.

// Dirección base del proyecto
$base = '/reto4-medicosdelmundo/';
// Guardamos en $base la carpeta raíz del proyecto.
// Así podemos construir rutas (enlaces, archivos) fácilmente.

// PASO 2: Si el usuario no es administrador (rol = 3), mandarlo al login
if (!isset($_SESSION['usuario_id']) || $_SESSION['id_rol'] != 3) {
    // Si no hay usuario guardado en memoria, O su tipo de usuario no es "3" (administrador)...
    header('Location: ' . $base . 'signin.php');
    // ...lo redirigimos automáticamente a la página de inicio de sesión.
    exit();
    // Paramos aquí: lo que viene después solo puede verlo un administrador.
}
 
// PASO 3: Conectar con la base de datos
include_once __DIR__ . "/../conexion.php";
// Cargamos el archivo que contiene los datos para conectarse a la base de datos
// (__DIR__ significa "la carpeta donde estoy ahora mismo").

$database = new Database();
// Creamos un objeto "Database" que sabe cómo conectarse.
$conn = $database->conectar();
// Abrimos la conexión y la guardamos en $conn para usarla después.
 
// Añadir la columna "ruta" a la tabla si todavía no existe
try {
    // "try" = intentamos hacer algo que podría fallar...
    $conn->exec("ALTER TABLE CATEGORIA ADD COLUMN IF NOT EXISTS ruta VARCHAR(255);");
    // Le pedimos a la base de datos que añada una columna llamada "ruta"
    // a la tabla CATEGORIA, pero SOLO si todavía no existe.
    // VARCHAR(255) significa que guardará texto de hasta 255 caracteres.
} catch (PDOException $e) {
    // Si falla (por ejemplo, porque ya existía), no hacemos nada y seguimos.
}
 
// Variables para mostrar mensajes en pantalla
$error   = '';
// Creamos una caja vacía donde guardaremos mensajes de error si algo sale mal.
$mensaje = '';
// Y otra caja para mensajes de éxito.
 
// ─────────────────────────────────────────────
// PASO 4: Si se envía el formulario para AÑADIR una categoría...
// ─────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'add') {
    // Si el usuario ha pulsado el botón "Enviar" del formulario (método POST)
    // y la acción indicada en el formulario es "add" (añadir)...
 
    $titulo      = trim($_POST['titulo']);
    // Recogemos el título que escribió el usuario y le quitamos espacios
    // al principio y al final (eso hace trim).
    $descripcion = trim($_POST['descripcion']);
    // Igual con la descripción.
    $icono       = trim($_POST['icono']);
    // Igual con el icono (una clase de FontAwesome, como "fa-users").
    $ruta        = trim($_POST['ruta']);
    // Igual con la ruta (la dirección del archivo que se creará).
    $id_madre    = ($_POST['id_madre'] != '') ? intval($_POST['id_madre']) : 'NULL';
    // Si el usuario eligió una categoría madre, guardamos su número.
    // Si no eligió ninguna, guardamos el texto 'NULL' (que en SQL significa "sin valor").
    // intval() convierte el valor a número entero para evitar datos raros.
 
    // Comprobaciones básicas antes de guardar
    if ($titulo == '') {
        $error = 'El título es obligatorio.';
        // Si el título está vacío, guardamos el mensaje de error y no guardamos nada.
 
    } elseif ($id_madre != 'NULL' && $ruta == '') {
        $error = 'Si añades una subcategoría debes indicar una ruta.';
        // Si es una subcategoría (tiene madre) pero no tiene ruta, avisamos del error.
 
    } elseif (strpos($ruta, '..') !== false) {
        // strpos busca si el texto ".." aparece dentro de la ruta.
        // ".." en rutas de archivos significa "subir una carpeta", lo cual es peligroso.
        $error = 'La ruta no puede contener "..".';
        // Si alguien intenta usar rutas peligrosas, lo bloqueamos.
 
    } else {
        // Si todo está bien, procedemos a guardar...

        $ruta_limpia = ltrim(str_replace('\\', '/', $ruta), '/');
        // Primero cambiamos las barras invertidas (\) por barras normales (/).
        // Luego quitamos la barra "/" del principio si la hubiera.
        // Así la ruta queda en un formato estándar y seguro.
        if ($ruta_limpia == '') $ruta_limpia = 'NULL';
        // Si después de limpiar la ruta quedó vacía, la ponemos como NULL.
 
        $fecha = date('Y-m-d');
        // Obtenemos la fecha de hoy en formato Año-Mes-Día (ej: 2025-04-21).
 
        // Construir y ejecutar la consulta SQL directamente
        try {
            $conn->exec("INSERT INTO CATEGORIA (titulo, descripcion, icono, id_madre, fecha_actualizacion, ruta)
                         VALUES ('$titulo', '$descripcion', '$icono', $id_madre, '$fecha', '$ruta_limpia')");
            // Le pedimos a la base de datos que INSERTE (guarde) una nueva fila
            // en la tabla CATEGORIA con todos los datos que recogimos.
 
            // ── Crear el archivo PHP en disco si se dio una ruta ──
            if ($ruta_limpia != 'NULL') {
                // Si el usuario indicó una ruta (no es NULL), creamos el archivo físico.

                $carpeta_proyecto = realpath(__DIR__ . '/../');
                // realpath() nos da la ruta absoluta real de la carpeta del proyecto
                // (subimos una carpeta desde donde estamos con '/../').
                $ruta_archivo     = $carpeta_proyecto . '/' . $ruta_limpia;
                // Combinamos la carpeta del proyecto con la ruta relativa del archivo.
 
                if (pathinfo($ruta_archivo, PATHINFO_EXTENSION) == '') {
                    $ruta_archivo .= '.php';
                }
                // pathinfo() analiza la ruta del archivo.
                // Si el archivo no tiene extensión (como ".php"), le añadimos ".php".
 
                $carpeta = dirname($ruta_archivo);
                // dirname() nos devuelve solo la parte de la carpeta, sin el nombre del archivo.
                if (!is_dir($carpeta)) {
                    mkdir($carpeta, 0755, true);
                    // Si esa carpeta no existe todavía, la creamos.
                    // 0755 define los permisos (quién puede leer/escribir).
                    // true permite crear varias carpetas anidadas a la vez.
                }
 
                if (!file_exists($ruta_archivo)) {
                    // Solo creamos el archivo si todavía no existe (para no sobreescribir).

                    $contenido  = "<?php include_once \$_SERVER['DOCUMENT_ROOT'] . '{$base}barrasNavegacion/headerDINAMICO.php'; ?>\n";
                    // Primera línea del archivo: incluye el menú de navegación dinámico del sitio.
                    $contenido .= "<!doctype html>\n<html lang='es'>\n<head>\n";
                    // Estructura básica de una página HTML.
                    $contenido .= "<meta charset='utf-8'>\n";
                    // Indicamos que el archivo usa el juego de caracteres UTF-8 (para tildes, etc.).
                    $contenido .= "<title>$titulo</title>\n";
                    // El título de la pestaña del navegador será el nombre de la categoría.
                    $contenido .= "</head>\n<body>\n";
                    $contenido .= "<h1>$titulo</h1>\n";
                    // Mostramos el título como encabezado grande en la página.
                    $contenido .= "<p>Contenido creado automáticamente.</p>\n";
                    // Texto de relleno para indicar que la página fue generada sola.
                    $contenido .= "</body>\n</html>";
                    // Cerramos la estructura HTML.
 
                    file_put_contents($ruta_archivo, $contenido);
                    // Escribimos todo ese texto en el archivo de disco.
                }
            }
 
            $mensaje = 'Categoría añadida correctamente.';
            // Si todo fue bien, guardamos el mensaje de éxito para mostrarlo en pantalla.
 
        } catch (PDOException $e) {
            $error = 'Error al guardar: ' . $e->getMessage();
            // Si algo falló al hablar con la base de datos, guardamos el mensaje de error.
        }
    }
}
 
// PASO 5: Si se pide ELIMINAR una categoría...
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    // Si en la URL aparece "?action=delete&id=X", es que el usuario quiere borrar
    // la categoría con el número X.
 
    $id = intval($_GET['id']);
    // Convertimos el número de la URL a un entero puro, por seguridad.
 
    try {
        $conn->exec("DELETE FROM CATEGORIA WHERE id_categoria = $id OR id_madre = $id");
        // Borramos de la base de datos la categoría con ese ID,
        // Y TAMBIÉN todas sus subcategorías (las que tienen ese ID como "madre").
 
        header('Location: ' . $base . 'Vistadmin/gestion_header.php');
        // Redirigimos al usuario de vuelta a esta misma página.
        exit();
        // Paramos la ejecución del código aquí.
 
    } catch (PDOException $e) {
        $error = 'Error al eliminar: ' . $e->getMessage();
        // Si hubo algún problema, guardamos el mensaje de error.
    }
}
 
// PASO 6: Leer todas las categorías para mostrarlas en pantalla
try {
    $stmt       = $conn->query("SELECT * FROM CATEGORIA ORDER BY id_madre NULLS FIRST, id_categoria ASC");
    // Pedimos a la base de datos TODAS las categorías.
    // Las ordenamos: primero las principales (sin madre), luego las subcategorías.
    // ASC = de menor a mayor número de ID.
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Guardamos todos los resultados en el array (lista) $categorias.
    // FETCH_ASSOC significa que cada fila será un array con nombres de columna como claves.
 
} catch (PDOException $e) {
    $categorias = [];
    // Si hay error, dejamos la lista vacía...
    $error      = 'Error al leer categorías: ' . $e->getMessage();
    // ...y guardamos el mensaje de error.
}
?>

<!doctype html>
<!-- Indicamos que este documento es HTML5 -->
<html lang="es">
<!-- La página está en español -->
<head>
    <meta charset="utf-8">
    <!-- Soporte para caracteres especiales como tildes y eñes -->
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- La página se adapta bien a móviles -->
    <title>Gestión del Header</title>
    <!-- Título que aparece en la pestaña del navegador -->
    <link rel="stylesheet" href="<?= $base ?>estilos.css">
    <!-- Enlazamos la hoja de estilos (CSS) que da aspecto visual a la página -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body>
    <header class="gestion-header-container">
        
        <a href="<?= $base ?>home/home.php" class="logo" style="margin-right:10px;">
			<img src="<?= $base ?>Imagenes/Logoreal.png" alt="Logo">
		</a>
        <?php
			if (intval($_SESSION['id_rol']) === 3) {
				echo '<button class="logoutbutton"><a href="' . $base . 'Vistadmin/Menu.php"><i class="fa-solid fa-house"></i>Menú de Administradora</a></button>';
			}
		?>
        <!-- Enlace para volver a la página principal del sitio -->
         <section class="admin-session">
			<?php
			if (isset($_SESSION['usuario_nombre'])) {
				$nombre = basename($_SESSION['usuario_nombre']);
				echo "<span class='admin-name'>Hola, " . $nombre . "</span>";
				echo "<button class='logoutbutton'>"; // Botón para cerrar sesión
				echo "<a href='" . $base . "logout.php'><i class=\"fa-solid fa-right-from-bracket\"></i>Cerrar sesión</a>";
				echo "</button>";
			}
			?>
		</section>
    </header>

    <main class="gestion-header-main">
        <h1>Gestión del Header (muy simple)</h1>
        <!-- Título principal de la página de administración -->

        <?php if ($error): ?>
            <p class="gestion-error"><?php echo $error; ?></p>
            <!-- Si hay algún error guardado, lo mostramos en pantalla en rojo -->
        <?php endif; ?>

        <section class="gestion-section">
            <h2>Añadir sección / subapartado</h2>
            <form method="post">
                <!-- Formulario que enviará los datos con método POST al pulsar el botón -->
                <input type="hidden" name="action" value="add">
                <!-- Campo invisible que le dice al PHP que la acción es "añadir" -->

                <p>
                    <label>Título:<br>
                    <input type="text" name="titulo" required class="gestion-input-300"></label>
                    <!-- Campo de texto obligatorio para el título de la categoría -->
                </p>
                <p>
                    <label>Descripción:<br>
                    <input type="text" name="descripcion" class="gestion-input-300"></label>
                    <!-- Campo de texto para la descripción (no obligatorio) -->
                </p>
                <p>
                    <label>Icono (clase font-awesome):<br>
                    <input type="text" name="icono" placeholder="fa-users" class="gestion-input-300"></label>
                    <!-- Campo para escribir el nombre del icono (ej: fa-users = icono de personas) -->
                </p>
                <p>
                    <label>Madre (dejar vacío si es sección principal):<br>
                    <select name="id_madre">
                        <!-- Lista desplegable para elegir si esta categoría pertenece a otra -->
                        <option value="">-- Ninguna --</option>
                        <!-- Opción por defecto: sin categoría madre -->
                        <?php foreach ($categorias as $c): if ($c['id_madre'] !== null) continue; ?>
                            <!-- Recorremos la lista de categorías; saltamos las que ya son subcategorías -->
                            <option value="<?= $c['id_categoria'] ?>"><?= htmlspecialchars($c['titulo']) ?></option>
                            <!-- Mostramos cada categoría principal como opción del desplegable -->
                            <!-- htmlspecialchars() evita que caracteres como < o > rompan el HTML -->
                        <?php endforeach; ?>
                    </select>
                    </label>
                </p>
                <p>
                    <label>Ruta (ej: contratosficticios/contratosretratos) — requerida para subapartados:<br>
                    <input type="text" name="ruta" class="gestion-input-400"></label>
                    <!-- Campo para escribir la ruta del archivo que se creará en disco -->
                </p>
                <p>
                    <button type="submit">Añadir</button>
                    <!-- Botón que envía el formulario -->
                </p>
            </form>
        </section>

        <section>
            <h2>Secciones actuales</h2>
            <ul>
                <!-- Lista con todas las categorías existentes -->
                <?php
                foreach ($categorias as $cat) {
                    // Recorremos cada categoría de la lista...
                    if ($cat['id_madre'] !== null) continue;
                    // ...pero si tiene madre (es subcategoría), la saltamos aquí;
                    // las subcategorías se muestran más abajo, dentro de su madre.

                    echo '<li><strong>' . htmlspecialchars($cat['titulo']) . '</strong> ';
                    // Mostramos el título de la categoría principal en negrita.
                    if (!empty($cat['ruta'])) echo '(<em>' . htmlspecialchars($cat['ruta']) . '</em>)';
                    // Si tiene ruta, la mostramos en cursiva entre paréntesis.
                    echo ' <a href="?action=delete&id=' . intval($cat['id_categoria']) . '" class="gestion-delete-link">Eliminar</a>';
                    // Mostramos el enlace "Eliminar" que, al pulsar, borra esta categoría.

                    echo '<ul>';
                    // Abrimos una lista interior para las subcategorías.
                    foreach ($categorias as $sub) {
                        // Recorremos de nuevo la lista buscando las hijas de esta categoría.
                        if ($sub['id_madre'] == $cat['id_categoria']) {
                            // Si la subcategoría pertenece a la categoría actual...
                            echo '<li>' . htmlspecialchars($sub['titulo']);
                            // Mostramos su título.
                            if (!empty($sub['ruta'])) echo ' (<em>' . htmlspecialchars($sub['ruta']) . '</em>)';
                            // Si tiene ruta, la mostramos también.
                            echo ' <a href="?action=delete&id=' . intval($sub['id_categoria']) . '" class="gestion-delete-link">Eliminar</a>';
                            // Mostramos el enlace para eliminar esta subcategoría.
                            echo '</li>';
                        }
                    }
                    echo '</ul>';
                    // Cerramos la lista interior de subcategorías.
                    echo '</li>';
                    // Cerramos el elemento de la categoría principal.
                }
                ?>
            </ul>
        </section>
    </main>
</body>
</html>