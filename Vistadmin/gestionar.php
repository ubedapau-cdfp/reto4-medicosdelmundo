<?php
session_start();
$base = '/reto4-medicosdelmundo/';
// Acceso para administradoras (id_rol === 3)
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['id_rol']) || intval($_SESSION['id_rol']) !== 3) {
    header('Location: /reto4-medicosdelmundo/signin.php');
    exit();
}

require_once '../conexion.php';
require_once '../clases/Categoria.php';
require_once '../clases/Bloque.php';

$db = new Database();
$conn = $db->conectar();

$categoryId = isset($_GET['id']) && is_numeric($_GET['id']) ? (int) $_GET['id'] : null;
$currentCategory = $categoryId ? Categoria::obtenerPorId($conn, $categoryId) : null;
$isMadre = $currentCategory ? $currentCategory->getIdMadre() === null : false;

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];
        if ($accion === 'eliminar_categoria' && isset($_POST['id_categoria'])) {
            Categoria::eliminar($conn, $_POST['id_categoria']);
        } elseif ($accion === 'guardar_categoria') {
            $id = $_POST['id_categoria'] ?? null;
            $titulo = $_POST['titulo'];
            $descripcion = $_POST['descripcion'];
            $icono = $_POST['icono'];
            if ($id) {
                $existingCategoria = Categoria::obtenerPorId($conn, $id);
                $id_madre = $existingCategoria ? $existingCategoria->getIdMadre() : null;
                Categoria::actualizar($conn, $id, $titulo, $descripcion, $icono, $id_madre);
            } else {
                $id_madre = ($currentCategory && $isMadre) ? $currentCategory->getIdCategoria() : null;
                Categoria::insertar($conn, $titulo, $descripcion, $icono, $id_madre);
            }
        } elseif ($accion === 'eliminar_bloque' && isset($_POST['id_bloque'])) {
            Bloque::eliminar($conn, $_POST['id_bloque']);
        } elseif ($accion === 'guardar_bloque') {
            $id = $_POST['id_bloque'] ?? null;
            $titulo = $_POST['titulo'];
            $subtitulo = $_POST['subtitulo'];
            $contenido = $_POST['contenido'];
            $orden = $_POST['orden'];
            $id_categoria = $_POST['id_categoria'];
            if ($id) {
                Bloque::actualizar($conn, $id, $titulo, $subtitulo, $contenido, $orden, $id_categoria);
            } else {
                Bloque::insertar($conn, $titulo, $subtitulo, $contenido, $orden, $id_categoria);
            }
        }
    }
}

$categorias = Categoria::obtenerTodas($conn);
$bloques = [];
$subcategorias = [];
if ($currentCategory) {
    if ($isMadre) {
        $subcategorias = Categoria::obtenerSubcategorias($conn, $categoryId);
    } else {
        $bloques = Bloque::obtenerPorCategoriaId($conn, $categoryId);
    }
}

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
                <p><?= htmlspecialchars($currentCategory->getDescripcion()) ?></p>
                <a href="<?= $base ?>Vistadmin/Menu.php" class="gestionar-btn">Volver al menú</a>
                <?php if ($isMadre): ?>
                    <a href="?id=<?= $categoryId ?>&nueva_categoria=1" class="gestionar-btn gestionar-btn-add">Añadir Subcategoría</a>
                <?php else: ?>
                    <a href="?id=<?= $categoryId ?>&nuevo_bloque=1" class="gestionar-btn gestionar-btn-add">Añadir Subcontenido</a>
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
                                <a href="?id=<?= $categoryId ?>&editar_categoria=<?= $sub->getIdCategoria() ?>" class="gestionar-btn gestionar-btn-edit">Editar</a>
                                <form method="post" action="?id=<?= $categoryId ?>" class="gestionar-form-inline">
                                    <input type="hidden" name="accion" value="eliminar_categoria">
                                    <input type="hidden" name="id_categoria" value="<?= $sub->getIdCategoria() ?>">
                                    <button type="submit" class="gestionar-btn gestionar-btn-delete" onclick="return confirm('¿Eliminar subcategoría?')">Eliminar</button>
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
                                <a href="?id=<?= $categoryId ?>&editar_bloque=<?= $bloque->getIdBloque() ?>" class="gestionar-btn gestionar-btn-edit">Editar</a>
                                <form method="post" action="?id=<?= $categoryId ?>" class="gestionar-form-inline">
                                    <input type="hidden" name="accion" value="eliminar_bloque">
                                    <input type="hidden" name="id_bloque" value="<?= $bloque->getIdBloque() ?>">
                                    <button type="submit" class="gestionar-btn gestionar-btn-delete" onclick="return confirm('¿Eliminar subcontenido?')">Eliminar</button>
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
                                <a href="?id=<?= $cat->getIdCategoria() ?>" class="gestionar-btn gestionar-btn-edit">Ver</a>
                                <a href="?editar_categoria=<?= $cat->getIdCategoria() ?>" class="gestionar-btn gestionar-btn-edit">Editar</a>
                                <form method="post" class="gestionar-form-inline">
                                    <input type="hidden" name="accion" value="eliminar_categoria">
                                    <input type="hidden" name="id_categoria" value="<?= $cat->getIdCategoria() ?>">
                                    <button type="submit" class="gestionar-btn gestionar-btn-delete" onclick="return confirm('¿Eliminar?')">Eliminar</button>
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
                <form method="post" action="<?= $currentCategory ? '?id=' . $categoryId : '' ?>" class="gestionar-form">
                    <input type="hidden" name="accion" value="guardar_categoria">
                    <?php if ($editar_categoria): ?>
                        <input type="hidden" name="id_categoria" value="<?= $editar_categoria->getIdCategoria() ?>">
                    <?php endif; ?>
                    <label class="gestionar-label">Título: <input type="text" name="titulo" class="gestionar-input" value="<?= $editar_categoria ? htmlspecialchars($editar_categoria->getTitulo()) : '' ?>" required></label>
                    <label class="gestionar-label">Descripción: <textarea name="descripcion" class="gestionar-textarea"><?= $editar_categoria ? htmlspecialchars($editar_categoria->getDescripcion()) : '' ?></textarea></label>
                    <label class="gestionar-label">Icono: <input type="text" name="icono" class="gestionar-input" value="<?= $editar_categoria ? htmlspecialchars($editar_categoria->getIcono()) : '' ?>"></label>
                    <?php if ($editar_categoria && $editar_categoria->getIdMadre()): ?>
                        <?php $parentCategory = Categoria::obtenerPorId($conn, $editar_categoria->getIdMadre()); ?>
                        <p>Subcategoría de: <strong><?= htmlspecialchars($parentCategory ? $parentCategory->getTitulo() : 'Desconocida') ?></strong></p>
                        <input type="hidden" name="id_madre" value="<?= $editar_categoria->getIdMadre() ?>">
                    <?php elseif ($currentCategory && $isMadre && !isset($editar_categoria)): ?>
                        <input type="hidden" name="id_madre" value="<?= $currentCategory->getIdCategoria() ?>">
                    <?php else: ?>
                        <input type="hidden" name="id_madre" value="">
                    <?php endif; ?>
                    <button type="submit" class="gestionar-btn gestionar-btn-edit">Guardar</button>
                    <a href="<?= $currentCategory ? '?id=' . $categoryId : '?' ?>" class="gestionar-btn">Cancelar</a>
                </form>
            </section>
        <?php endif; ?>

        <?php if ($editar_bloque || (isset($_GET['nuevo_bloque']) && $currentCategory && !$isMadre)): ?>
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
                    <button type="submit" class="gestionar-btn gestionar-btn-edit"><i class="fa-solid fa-floppy-disk"></i>Guardar</button>
                    <a href="?id=<?= $categoryId ?>" class="gestionar-btn">Cancelar</a>
                </form>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>
