<?php
session_start();
$base = '/reto4-medicosdelmundo/';

// Acceso sólo para administradoras (id_rol === 3)
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['id_rol']) || intval($_SESSION['id_rol']) !== 3) {
    header('Location: /reto4-medicosdelmundo/signin.php');
    exit();
}

require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../clases/Categoria.php';

$database = new Database();
$db = $database->conectar();

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $titulo = trim($_POST['titulo'] ?? '');
        $icono = trim($_POST['icono'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if ($titulo === '') {
            $error = 'El título es obligatorio para crear una entrada del menú.';
        } else {
            $creado = Categoria::crearCategoriaMadre($db, $titulo, $descripcion ?: null, $icono ?: null);
            if ($creado) {
                $mensaje = 'Entrada del header creada correctamente.';
            } else {
                $error = 'No se pudo crear la entrada. Inténtalo de nuevo.';
            }
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['delete_id'])) {
        $deleteId = intval($_POST['delete_id']);
        if ($deleteId <= 0) {
            $error = 'ID inválido para eliminar la entrada.';
        } else {
            if (Categoria::tieneSubcategorias($db, $deleteId)) {
                $error = 'No se puede eliminar una entrada que tiene subcategorías. Primero elimina sus subcategorías.';
            } elseif (Categoria::tieneBloques($db, $deleteId)) {
                $error = 'No se puede eliminar una entrada que tiene contenidos asociados. Primero elimina esos contenidos o cambia su categoría.';
            } else {
                $eliminado = Categoria::eliminarPorId($db, $deleteId);
                if ($eliminado) {
                    $mensaje = 'Entrada del header eliminada correctamente.';
                } else {
                    $error = 'No se pudo eliminar la entrada. Inténtalo de nuevo.';
                }
            }
        }
    }
}

$categorias = Categoria::obtenerCategoriasMadre($db);
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión Header</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= $base ?>estilos.css">
    <link rel="icon" type="image/png" href="<?= $base ?>Imagenes/Logoreal.png">
</head>
<body>
    <?php include __DIR__ . '/../barrasNavegacion/headeradmin.php'; ?>
    <main class="gestion-header-container">
        <section class="gestion-header-intro">
            <h1>Gestión de entradas del header</h1>
            <p>Desde aquí puedes añadir y quitar las entradas principales que aparecen en el menú.</p>
        </section>

        <?php if ($mensaje): ?>
            <section class="mensaje-exito"><?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?></section>
        <?php endif; ?>
        <?php if ($error): ?>
            <section class="mensaje-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></section>
        <?php endif; ?>

        <section class="gestion-header-listado">
            <h2>Entradas actuales</h2>
            <?php if (empty($categorias)): ?>
                <p>No hay entradas en el menú principal.</p>
            <?php else: ?>
                <table class="tabla-gestion-header">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Icono</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categorias as $categoria): ?>
                            <tr>
                                <td><?= htmlspecialchars($categoria->getIdCategoria(), ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($categoria->getTitulo(), ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($categoria->getIcono(), ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($categoria->getDescripcion(), ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <form method="post" style="display:inline;" onsubmit="return confirm('Eliminar esta entrada del header?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="delete_id" value="<?= htmlspecialchars($categoria->getIdCategoria(), ENT_QUOTES, 'UTF-8') ?>">
                                        <button type="submit" class="btn-eliminar">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>

        <section class="gestion-header-formulario">
            <h2>Añadir entrada al header</h2>
            <form method="post">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label for="titulo">Título</label>
                    <input id="titulo" name="titulo" type="text" required>
                </div>
                <div class="form-group">
                    <label for="icono">Icono Font Awesome</label>
                    <input id="icono" name="icono" type="text" placeholder="fa-solid fa-star">
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción (opcional)</label>
                    <textarea id="descripcion" name="descripcion" rows="3"></textarea>
                </div>
                <button type="submit" class="btn-guardar">Crear entrada</button>
            </form>
        </section>
    </main>
</body>
</html>
