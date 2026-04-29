<?php
session_start();
$base = '/reto4-medicosdelmundo/';
// Acceso para administradoras (id_rol === 3)
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['id_rol']) || intval($_SESSION['id_rol']) !== 3) {
    header('Location: /reto4-medicosdelmundo/signin.php');
    exit();
}

require_once '../conexion.php'; // conexión a la base de datos
require_once '../clases/Categoria.php'; // clase para gestionar categorías
require_once '../clases/Bloque.php'; // clase para gestionar bloques de contenido

$db = new Database(); // Instanciamos la clase Database
$conn = $db->conectar(); // Obtenemos la conexión a la base de datos

$categoryId = isset($_GET['id']) && is_numeric($_GET['id']) ? (int) $_GET['id'] : null; /* Obtenemos el id de la categoría seleccionada desde la URL si existe. */
$currentCategory = $categoryId ? Categoria::obtenerPorId($conn, $categoryId) : null; /* Si hay un id válido, obtenemos la categoría actual para mostrar su información y gestionar su contenido. */
$isMadre = $currentCategory ? $currentCategory->getIdMadre() === null : false; /* Determinamos si la categoría actual es una categoría madre (sin id_madre) o una subcategoría (con id_madre). Esto nos ayudará a mostrar las opciones de gestión adecuadas. */

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        // En este campo recibimos la acción principal que el formulario quiere ejecutar.
        $accion = $_POST['accion'];

        // Eliminar una categoría (madre o subcategoría).
        if ($accion === 'eliminar_categoria' && isset($_POST['id_categoria'])) {
            $categoria = Categoria::obtenerPorId($conn, $_POST['id_categoria']);
            if ($categoria) {
                $categoria->eliminar($conn);
            }

        // Guardar una categoría nueva o editar una existente.
        } elseif ($accion === 'guardar_categoria') {
            $id = $_POST['id_categoria'] ?? null;
            $titulo = trim($_POST['titulo'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $icono = trim($_POST['icono'] ?? '');
            // id_madre puede ser null cuando se crea una categoría principal.
            $id_madre = isset($_POST['id_madre']) && $_POST['id_madre'] !== '' ? (int) $_POST['id_madre'] : null;

            if ($id) {
                // Si hay id, estamos actualizando una categoría existente.
                $existingCategoria = Categoria::obtenerPorId($conn, $id);
                if ($existingCategoria) {
                    $existingCategoria->setTitulo($titulo);
                    $existingCategoria->setDescripcion($descripcion);
                    $existingCategoria->setIcono($icono);
                    $existingCategoria->actualizar($conn);
                }
            } else {
                // Si no hay id, creamos una nueva categoría.
                $nuevaCategoria = new Categoria(0, $titulo, $descripcion, $icono, $id_madre);
                $nuevaCategoria->insertar($conn);
            }

        // Eliminar un bloque de contenido asociado a una categoría.
        } elseif ($accion === 'eliminar_bloque' && isset($_POST['id_bloque'])) {
            $bloque = Bloque::obtenerPorId($conn, $_POST['id_bloque']);
            if ($bloque) {
                $bloque->eliminar($conn);
            }

        // Guardar un bloque de contenido nuevo o actualizado.
        } elseif ($accion === 'guardar_bloque') {
            $id = $_POST['id_bloque'] ?? null;
            $titulo = trim($_POST['titulo'] ?? '');
            $subtitulo = trim($_POST['subtitulo'] ?? '');
            $contenido = trim($_POST['contenido'] ?? '');
            $orden = intval($_POST['orden'] ?? 0);
            $id_categoria = intval($_POST['id_categoria'] ?? 0);

            if ($id) {
                $existingBloque = Bloque::obtenerPorId($conn, $id);
                if ($existingBloque) {
                    $existingBloque->setTitulo($titulo);
                    $existingBloque->setSubtitulo($subtitulo);
                    $existingBloque->setContenido($contenido);
                    $existingBloque->setOrden($orden);
                    $existingBloque->actualizar($conn);
                }
            } else {
                $nuevoBloque = new Bloque(0, $id_categoria, $titulo, $subtitulo, $contenido, $orden);
                $nuevoBloque->insertar($conn);
            }
        }

        // Después de procesar el formulario, redirigimos para evitar resubmisiones.
        $redirectUrl = $base . 'Vistadmin/gestionar.php';
        if ($categoryId) {
            $redirectUrl .= '?id=' . $categoryId; // Si estamos dentro de una categoría específica, redirigimos a esa misma categoría para seguir gestionándola después de la acción.
        }
        header('Location: ' . $redirectUrl); // Redirigimos a la página de gestión después de procesar la acción para evitar resubmisiones del formulario al refrescar.
        exit();
    }
}

// Cargamos todas las categorías para mostrar la lista principal.
$categorias = Categoria::obtenerTodas($conn); // Obtenemos todas las categorías para mostrar en la lista principal, independientemente de si son madres o subcategorías.

// Inicializamos los arrays que pueden contener subcategorías o bloques según la categoría seleccionada.
$bloques = [];
$subcategorias = [];

if ($currentCategory) {
    // Si la categoría actual es madre, cargamos sus subcategorías.
    if ($isMadre) {
        $subcategorias = Categoria::obtenerSubcategorias($conn, $categoryId);
    } else {
        // Si la categoría actual es una subcategoría, cargamos su contenido en bloques.
        $bloques = Bloque::obtenerPorCategoriaId($conn, $categoryId);
    }
}

// Si se solicitan editar una categoría o un bloque, cargamos los datos correspondientes.
$editar_categoria = null;
$editar_bloque = null;
if (isset($_GET['editar_categoria'])) {
    $editar_categoria = Categoria::obtenerPorId($conn, $_GET['editar_categoria']);
}
if (isset($_GET['editar_bloque'])) {
    $editar_bloque = Bloque::obtenerPorId($conn, $_GET['editar_bloque']);
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Gestionar Contenido</title>
    <link rel="stylesheet" href="<?= $base ?>estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/png" href="<?= $base ?>Imagenes/Logoreal.png">
</head>
<body>
    <?php include '../barrasNavegacion/headeradmin.php'; ?>
    <main class="gestionar-main">
        <h1>Gestionar Apartados y Subapartados</h1>

        <?php if ($currentCategory): ?>
            <section class="gestion-section">
                <h2><?= htmlspecialchars($currentCategory->getTitulo()) ?></h2>
                <p class="descripcionapartados"><?= htmlspecialchars($currentCategory->getDescripcion()) ?></p>
                <a href="<?= $base ?>Vistadmin/Menu.php" class="menubutton"><i class="fa-solid fa-arrow-left"></i>Volver al menú</a>
                <?php if ($isMadre): ?>
                    <a href="?id=<?= $categoryId ?>&nueva_categoria=1" class="menubutton"><i class="fa-solid fa-plus"></i>Añadir Subcategoría</a>
                <?php else: ?>
                    <a href="?id=<?= $categoryId ?>&nuevo_bloque=1" class="menubutton"><i class="fa-solid fa-plus"></i>Añadir Subcontenido</a>
                <?php endif; ?>
            </section>
        <?php endif; ?>

        <?php if ($currentCategory && $isMadre): ?>
            <section class="gestion-section">
                <h2>Subcategorías de <?= htmlspecialchars($currentCategory->getTitulo()) ?></h2>
                <table class="gestionar-table">
                    <tr><th class="gestionar-th">Título</th><th class="gestionar-th">Descripción</th><th class="gestionar-th">Icono</th><th class="gestionar-th">Acciones</th></tr>
                    <?php foreach ($subcategorias as $sub): ?>
                        <tr>
                            <td class="gestionar-td"><?= htmlspecialchars($sub->getTitulo()) ?></td>
                            <td class="gestionar-td"><?= htmlspecialchars($sub->getDescripcion()) ?></td>
                            <td class="gestionar-td"><?= htmlspecialchars($sub->getIcono()) ?></td>
                            <td class="gestionar-td">
                                <a href="?id=<?= $categoryId ?>&editar_categoria=<?= $sub->getIdCategoria() ?>" class="editbutton"><i class="fas fa-pencil"></i> Editar</a>
                                <form method="post" action="?id=<?= $categoryId ?>" class="gestionar-form-inline">
                                    <input type="hidden" name="accion" value="eliminar_categoria">
                                    <input type="hidden" name="id_categoria" value="<?= $sub->getIdCategoria() ?>">
                                    <button type="submit" class="deletebutton" onclick="return confirm('¿Eliminar subcategoría?')"><i class="fas fa-trash"></i> Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <?php if (empty($subcategorias)): ?>
                    <p>No hay subcategorías para esta categoría.</p>
                <?php endif; ?>
            </section>
        <?php elseif ($currentCategory && !$isMadre): ?>
            <section class="gestion-section">
                <h2>Subcontenido de <?= htmlspecialchars($currentCategory->getTitulo()) ?></h2>
                <table class="gestionar-table">
                    <tr><th class="gestionar-th">Título</th><th class="gestionar-th">Subtítulo</th><th class="gestionar-th">Contenido</th><th class="gestionar-th">Orden</th><th class="gestionar-th">Acciones</th></tr>
                    <?php foreach ($bloques as $bloque): ?>
                        <tr>
                            <td class="gestionar-td"><?= htmlspecialchars($bloque->getTitulo()) ?></td>
                            <td class="gestionar-td"><?= htmlspecialchars($bloque->getSubtitulo()) ?></td>
                            <td class="gestionar-td"><?= htmlspecialchars(substr($bloque->getContenido(), 0, 80)) ?>...</td>
                            <td class="gestionar-td"><?= $bloque->getOrden() ?></td>
                            <td class="gestionar-td">
                                <a href="?id=<?= $categoryId ?>&editar_bloque=<?= $bloque->getIdBloque() ?>" class="editbutton"><i class="fas fa-pencil"></i> Editar</a>
                                <form method="post" action="?id=<?= $categoryId ?>" class="gestionar-form-inline">
                                    <input type="hidden" name="accion" value="eliminar_bloque">
                                    <input type="hidden" name="id_bloque" value="<?= $bloque->getIdBloque() ?>">
                                    <button type="submit" class="deletebutton" onclick="return confirm('¿Eliminar subcontenido?')"><i class="fas fa-trash"></i> Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <?php if (empty($bloques)): ?>
                    <p>No hay subcontenido para esta categoría.</p>
                <?php endif; ?>
            </section>
        <?php else: ?>
            <section class="gestion-section">
                <h2>Categorías principales</h2>
                <table class="gestionar-table">
                    <tr><th class="gestionar-th">Título</th><th class="gestionar-th">Descripción</th><th class="gestionar-th">Icono</th><th class="gestionar-th">Tipo</th><th class="gestionar-th">Acciones</th></tr>
                    <?php foreach ($categorias as $cat): ?>
                        <tr>
                            <td class="gestionar-td"><?= htmlspecialchars($cat->getTitulo()) ?></td>
                            <td class="gestionar-td"><?= htmlspecialchars($cat->getDescripcion()) ?></td>
                            <td class="gestionar-td"><?= htmlspecialchars($cat->getIcono()) ?></td>
                            <td class="gestionar-td"><?= $cat->getIdMadre() ? 'Subcategoría' : 'Principal' ?></td>
                            <td class="gestionar-td">
                                <a href="?id=<?= $cat->getIdCategoria() ?>" class="viewbutton"><i class="fas fa-eye"></i> Ver</a>
                                <a href="?editar_categoria=<?= $cat->getIdCategoria() ?>" class="editbutton"><i class="fas fa-pencil"></i> Editar</a>
                                <form method="post" class="gestionar-form-inline">
                                    <input type="hidden" name="accion" value="eliminar_categoria">
                                    <input type="hidden" name="id_categoria" value="<?= $cat->getIdCategoria() ?>">
                                    <button type="submit" class="deletebutton" onclick="return confirm('¿Eliminar?')"><i class="fas fa-trash"></i> Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <a href="?nueva_categoria=1" class="gestionar-btn gestionar-btn-add">Añadir Nueva Categoría</a>
            </section>
        <?php endif; ?>

        <?php if ($editar_categoria || (isset($_GET['nueva_categoria']) && (!$currentCategory || $isMadre))): ?>
            <section class="gestionar-section">
                <h3><?= $editar_categoria ? 'Editar' : 'Nueva' ?> Categoría</h3>
                <!-- Formulario para crear o editar una categoría. -->
                <form method="post" action="<?= $currentCategory ? '?id=' . $categoryId : '' ?>" class="gestionar-form">
                    <input type="hidden" name="accion" value="guardar_categoria">

                    <!-- Si editamos una categoría existente, guardamos su id oculta. -->
                    <?php if ($editar_categoria): ?>
                        <input type="hidden" name="id_categoria" value="<?= $editar_categoria->getIdCategoria() ?>">
                    <?php endif; ?>

                    <label class="gestionar-label">
                        Título:
                        <input type="text" name="titulo" class="gestionar-input" value="<?= $editar_categoria ? htmlspecialchars($editar_categoria->getTitulo()) : '' ?>" required>
                    </label>

                    <label class="gestionar-label">
                        Descripción:
                        <textarea name="descripcion" class="gestionar-textarea"><?= $editar_categoria ? htmlspecialchars($editar_categoria->getDescripcion()) : '' ?></textarea>
                    </label>

                    <!-- Valor del icono se guarda como texto en la base de datos. -->
                    <label class="gestionar-label">
                        Icono:
                        <input type="text" name="icono" class="gestionar-input" value="<?= $editar_categoria ? htmlspecialchars($editar_categoria->getIcono()) : '' ?>">
                    </label>

                    <?php if ($editar_categoria && $editar_categoria->getIdMadre()): ?>
                        <!-- Si editamos una subcategoría, mostramos su categoría padre. -->
                        <?php $parentCategory = Categoria::obtenerPorId($conn, $editar_categoria->getIdMadre()); ?>
                        <p>Subcategoría de: <strong><?= htmlspecialchars($parentCategory ? $parentCategory->getTitulo() : 'Desconocida') ?></strong></p>
                        <input type="hidden" name="id_madre" value="<?= $editar_categoria->getIdMadre() ?>">
                    <?php elseif ($currentCategory && $isMadre && !isset($editar_categoria)): ?>
                        <!-- Si estamos dentro de una categoría madre y vamos a crear subcategorías nuevas. -->
                        <input type="hidden" name="id_madre" value="<?= $currentCategory->getIdCategoria() ?>">
                    <?php else: ?>
                        <!-- Si creamos una categoría principal desde la lista principal. -->
                        <input type="hidden" name="id_madre" value="">
                    <?php endif; ?>
                    <button type="submit" class="savebutton"><i class="fa-solid fa-floppy-disk"></i>Guardar</button>
                    <a href="<?= $currentCategory ? '?id=' . $categoryId : '?' ?>" class="deletebutton"><i class="fa-solid fa-times"></i> Cancelar</a>
                </form>
            </section>
        <?php endif; ?>

        <?php if ($editar_bloque || (isset($_GET['nuevo_bloque']) && $currentCategory && !$isMadre)): ?>
            <!-- Si se solicita crear o editar un bloque, se muestra este formulario. -->
            <!-- Formulario para crear o editar bloques dentro de una subcategoría. -->
            <section class="gestionar-section">
                <h3><?= $editar_bloque ? 'Editar' : 'Nuevo' ?> Bloque</h3>
                <form method="post" action="?id=<?= $categoryId ?>" class="gestionar-form">
                    <input type="hidden" name="accion" value="guardar_bloque">
                    <?php if ($editar_bloque): ?>
                        <input type="hidden" name="id_bloque" value="<?= $editar_bloque->getIdBloque() ?>">
                    <?php endif; ?>
                    <label class="gestionar-label">Título: <input type="text" name="titulo" class="gestionar-input" value="<?= $editar_bloque ? htmlspecialchars($editar_bloque->getTitulo()) : '' ?>" required></label>
                    <label class="gestionar-label">Subtítulo: <input type="text" name="subtitulo" class="gestionar-input" value="<?= $editar_bloque ? htmlspecialchars($editar_bloque->getSubtitulo()) : '' ?>"></label>
                    <label class="gestionar-label">Contenido: <textarea name="contenido" class="gestionar-textarea" required><?= $editar_bloque ? htmlspecialchars($editar_bloque->getContenido()) : '' ?></textarea></label>
                    <label class="gestionar-label">Orden: <input type="number" name="orden" class="gestionar-input" value="<?= $editar_bloque ? $editar_bloque->getOrden() : '' ?>" required></label>
                    <?php if ($editar_bloque): ?>
                        <input type="hidden" name="id_categoria" value="<?= $editar_bloque->getIdCategoria() ?>">
                    <?php else: ?>
                        <input type="hidden" name="id_categoria" value="<?= $categoryId ?>">
                    <?php endif; ?>
                    <button type="submit" class="savebutton"><i class="fa-solid fa-floppy-disk"></i>Guardar</button>
                    <a href="?id=<?= $categoryId ?>" class="deletebutton"><i class="fa-solid fa-times"></i> Cancelar</a>
                </form>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>
