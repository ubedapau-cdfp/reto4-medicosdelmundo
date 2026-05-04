<?php
session_start();
$base = '/reto4-medicosdelmundo/';
// Acceso para orientadoras y administradoras (id_rol === 2, 3)
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['id_rol']) || !in_array(intval($_SESSION['id_rol']), [2, 3], true)) {
    header('Location: /reto4-medicosdelmundo/signin.php');
    exit();
}

require_once '../conexion.php'; // conexión a la base de datos
require_once '../clases/Categoria.php'; // clase para gestionar categorías
require_once '../clases/Bloque.php'; // clase para gestionar bloques de contenido
require_once '../clases/Faq.php'; // clase para gestionar preguntas frecuentes

$db = new Database(); // Instanciamos la clase Database
$conn = $db->conectar(); // Obtenemos la conexión a la base de datos

$categoryId = isset($_GET['id']) && is_numeric($_GET['id']) ? (int) $_GET['id'] : null; // Obtenemos el id de la categoría seleccionada desde la URL si existe.
$currentCategory = $categoryId ? Categoria::obtenerPorId($conn, $categoryId) : null; // Si hay un id válido, obtenemos la categoría actual para mostrar su información y gestionar su contenido.
$isMadre = $currentCategory ? $currentCategory->getIdMadre() === null : false; 
// Determinamos si la categoría actual es una categoría madre (sin id_madre) o una subcategoría (con id_madre). Esto nos ayudará a mostrar las opciones de gestión adecuadas.

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Si el formulario se ha enviado, procesamos la acción correspondiente.
    if (isset($_POST['accion'])) { // Si existe accion en el POST
        $accion = $_POST['accion']; // Obtenemos la acción y la guardamos en una variable

        if ($accion === 'eliminar_categoria' && isset($_POST['id_categoria'])) { // Si la accion es eliminar_categoria y existe id_categoria en el POST
            $categoria = Categoria::obtenerPorId($conn, $_POST['id_categoria']); // Obtenemos la categoría a eliminar por su id
            if ($categoria) { // Si la categoría existe
                $categoria->eliminar($conn); // Llamamos a eliminar en la clase Categoria
            }

        // Guardar una categoría nueva o editar una existente.
        } elseif ($accion === 'guardar_categoria') { // Si la accion es guardar_categoria
            $id = $_POST['id_categoria'] ?? null; // Guardamos en el $id, si no, es null
            $titulo = trim($_POST['titulo'] ?? ''); // Guardamos el titulo, si no, es vacío
            $descripcion = trim($_POST['descripcion'] ?? ''); // Guardamos la descripción, si no, es vacío
            $icono = trim($_POST['icono'] ?? ''); // Guardamos el icono, si no, es vacío
            // id_madre puede ser null cuando se crea una categoría principal.
            $id_madre = isset($_POST['id_madre']) && $_POST['id_madre'] !== '' ? (int) $_POST['id_madre'] : null; // Guardamos el id_madre, si no, es null.

            if ($id) {
                // Si hay id, estamos actualizando una categoría existente.
                $existingCategoria = Categoria::obtenerPorId($conn, $id); // Obtenemos la categoría existente por su id para actualizarla.
                if ($existingCategoria) { // Si la categoría existe
                    $existingCategoria->setTitulo($titulo); // Actualizamos el título
                    $existingCategoria->setDescripcion($descripcion); // Actualizamos la descripción
                    $existingCategoria->setIcono($icono); // Actualizamos el icono
                    $existingCategoria->actualizar($conn); // Llamamos a actualizar en la clase Categoria para guardar los cambios en la base de datos.
                }
            } else {
                // Si no hay id, creamos una nueva categoría.
                $nuevaCategoria = new Categoria(0, $titulo, $descripcion, $icono, $id_madre); // Creamos nueva Categoría, con id = 0, pues se asignará automáticamente
                $nuevaCategoria->insertar($conn); // Llamamos a insertar en la clase Categoria para guardar la nueva categoría en la base de datos.
            }

        // Eliminar un bloque de contenido asociado a una categoría.
        } elseif ($accion === 'eliminar_bloque' && isset($_POST['id_bloque'])) { // Si la acción es eliminar_bloque y existe id_bloque en el POST
            $bloque = Bloque::obtenerPorId($conn, $_POST['id_bloque']); // Obtenemos el bloque a eliminar por su id
            if ($bloque) { // Si el bloque existe
                $bloque->eliminar($conn); // Llamamos a eliminar en la clase Bloque para eliminar el bloque de contenido de la base de datos.
            }

        // Guardar un bloque de contenido nuevo o actualizado.
        } elseif ($accion === 'guardar_bloque') { // Si la acción es guardar_bloque
            $id = $_POST['id_bloque'] ?? null; // Guardamos en $id el id del bloque, si no existe, es null
            $titulo = trim($_POST['titulo'] ?? ''); // Guardamos el título del bloque, si no existe, es vacío
            $subtitulo = trim($_POST['subtitulo'] ?? ''); // Guardamos el subtítulo del bloque, si no existe, es vacío
            $contenido = trim($_POST['contenido'] ?? ''); // Guardamos el contenido del bloque, si no existe, es vacío
            $orden = intval($_POST['orden'] ?? 0); // Guardamos el orden del bloque, si no existe, es 0
            $id_categoria = intval($_POST['id_categoria'] ?? 0); // Guardamos el id de la categoría a la que pertenece el bloque, si no existe, es 0

            if ($id) { // Si hay id
                $existingBloque = Bloque::obtenerPorId($conn, $id); // Obtenemos el bloque existente por su id para actualizarlo.
                if ($existingBloque) { // Si el bloque existe
                    $existingBloque->setTitulo($titulo); // Actualizamos el título del bloque
                    $existingBloque->setSubtitulo($subtitulo); // Actualizamos el subtítulo del bloque
                    $existingBloque->setContenido($contenido); // Actualizamos el contenido del bloque
                    $existingBloque->setOrden($orden); // Actualizamos el orden del bloque
                    $existingBloque->actualizar($conn); // Llamamos a actualizar en la clase Bloque para guardar los cambios del bloque en la base de datos.
                }
            } else {
                $nuevoBloque = new Bloque(0, $id_categoria, $titulo, $subtitulo, $contenido, $orden); // Creamos un nueva Categoría con id = 0, pues se asignará automáticamente, junto a su id_categoria correspondiente.
                $nuevoBloque->insertar($conn); // Llamamos a insertar en la clase Bloque para guardar el nuevo bloque de contenido en la base de datos.
            }

        // Eliminar una FAQ
        } elseif ($accion === 'eliminar_faq' && isset($_POST['id_faq'])) {
            $faq = Faq::obtenerPorId($conn, $_POST['id_faq']);
            if ($faq) {
                $faq->eliminar($conn);
            }

        // Guardar una FAQ nueva o editada
        } elseif ($accion === 'guardar_faq') {
            $id = $_POST['id_faq'] ?? null;
            $pregunta = trim($_POST['pregunta'] ?? '');
            $respuesta = trim($_POST['respuesta'] ?? '');
            $id_categoria = intval($_POST['id_categoria'] ?? 0);

            if ($id) {
                $existingFaq = Faq::obtenerPorId($conn, $id);
                if ($existingFaq) {
                    $existingFaq->setPregunta($pregunta);
                    $existingFaq->setRespuesta($respuesta);
                    $existingFaq->setIdCategoria($id_categoria);
                    $existingFaq->actualizar($conn);
                }
            } else {
                $nuevoFaq = new Faq(0, $pregunta, $respuesta, $id_categoria);
                $nuevoFaq->insertar($conn);
            }

        // Después de procesar el formulario, redirigimos para evitar resubmisiones.
        $redirectUrl = $base . 'Vistadmin/gestionar.php'; // Redirigimos a la página de gestionar.php
        if ($categoryId) { // Si hay una categoría seleccionada, añadimos su id a la URL para redirigir a esa categoría
            $redirectUrl .= '?id=' . $categoryId; // Si estamos dentro de una categoría específica, redirigimos a esa misma categoría para seguir gestionándola después de la acción.
        }
        header('Location: ' . $redirectUrl); // Redirigimos a la página de gestión después de procesar la acción para evitar resubmisiones del formulario al refrescar.
        exit();
    }
}
}

$categorias = Categoria::obtenerTodas($conn); // Obtenemos todas las categorías para mostrar en la lista principal, independientemente de si son madres o subcategorías.

$bloques = []; // Array para almacenar los bloques de contenido si la categoría actual es una subcategoría.
$subcategorias = []; // Array para almacenar las subcategorías si la categoría actual es una categoría madre.
$faqs = []; // Array para almacenar las FAQs de la categoría actual.

if ($currentCategory) {
    // Si la categoría actual es madre, cargamos sus subcategorías.
    if ($isMadre) { //
        $subcategorias = Categoria::obtenerSubcategorias($conn, $categoryId); //
    } else {
        // Si la categoría actual es una subcategoría, cargamos su contenido en bloques.
        $bloques = Bloque::obtenerPorCategoriaId($conn, $categoryId);
    }
    // Cargamos las FAQs de la categoría actual
    $faqs = Faq::obtenerPorCategoria($conn, $categoryId);
}

// Si se solicitan editar una categoría o un bloque, cargamos los datos correspondientes.
$editar_categoria = null;
$editar_bloque = null;
$editar_faq = null;
if (isset($_GET['editar_categoria'])) {
    $editar_categoria = Categoria::obtenerPorId($conn, $_GET['editar_categoria']);
}
if (isset($_GET['editar_bloque'])) {
    $editar_bloque = Bloque::obtenerPorId($conn, $_GET['editar_bloque']);
}
if (isset($_GET['editar_faq'])) {
    $editar_faq = Faq::obtenerPorId($conn, $_GET['editar_faq']);
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

        <?php if ($currentCategory): ?> <!-- Si hay una categoría seleccionada, mostramos su información y opciones de gestión. -->
            <section class="gestion-section">
                <h2><?= htmlspecialchars($currentCategory->getTitulo()) ?></h2>
                <p class="descripcionapartados"><?= htmlspecialchars($currentCategory->getDescripcion()) ?></p>
                <?php if (isset($_SESSION['id_rol']) && intval($_SESSION['id_rol']) === 3): ?>
                    <a href="<?= $base ?>Vistadmin/Menu.php" class="menubutton"><i class="fa-solid fa-arrow-left"></i>Volver al menú</a>
                <?php else: ?>
                    <a href="<?= $base ?>VistaOrientadora/Menu.php" class="menubutton"><i class="fa-solid fa-arrow-left"></i>Volver al menú</a>
                <?php endif; ?>
                <?php if ($isMadre): ?>
                    <a href="?id=<?= $categoryId ?>&nueva_categoria=1" class="menubutton"><i class="fa-solid fa-plus"></i>Añadir Subcategoría</a>
                <?php else: ?>
                    <a href="?id=<?= $categoryId ?>&nuevo_bloque=1" class="menubutton"><i class="fa-solid fa-plus"></i>Añadir Subcontenido</a>
                <?php endif; ?>
            </section>
        <?php endif; ?>

        <?php if ($currentCategory && $isMadre): ?> <!-- Si hay una categoría seleccionada y es madre, mostramos sus subcategorías. -->
            <section class="gestion-section">
                <h2>Subcategorías de <?= htmlspecialchars($currentCategory->getTitulo()) ?></h2>
                <table class="gestionar-table">
                    <tr><th class="gestionar-th">Título</th><th class="gestionar-th">Descripción</th><th class="gestionar-th">Icono</th><th class="gestionar-th">Acciones</th></tr>
                    <?php foreach ($subcategorias as $sub): ?> <!-- Recorremos las subcategorías de la categoría madre actual para mostrarlas en una tabla con opciones de gestión. -->
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

            <!-- Sección de FAQs -->
            <section class="gestion-section">
                <h2>Preguntas Frecuentes (FAQs)</h2>
                <a href="?id=<?= $categoryId ?>&nuevo_faq=1" class="menubutton"><i class="fa-solid fa-plus"></i>Añadir FAQ</a>
                <table class="gestionar-table">
                    <tr><th class="gestionar-th">Pregunta</th><th class="gestionar-th">Respuesta</th><th class="gestionar-th">Acciones</th></tr>
                    <?php foreach ($faqs as $faq): ?>
                        <tr>
                            <td class="gestionar-td"><?= htmlspecialchars($faq->getPregunta()) ?></td>
                            <td class="gestionar-td"><?= htmlspecialchars(substr($faq->getRespuesta(), 0, 80)) ?>...</td>
                            <td class="gestionar-td">
                                <a href="?id=<?= $categoryId ?>&editar_faq=<?= $faq->getIdFaq() ?>" class="editbutton"><i class="fas fa-pencil"></i> Editar</a>
                                <form method="post" action="?id=<?= $categoryId ?>" class="gestionar-form-inline">
                                    <input type="hidden" name="accion" value="eliminar_faq">
                                    <input type="hidden" name="id_faq" value="<?= $faq->getIdFaq() ?>">
                                    <button type="submit" class="deletebutton" onclick="return confirm('¿Eliminar FAQ?')"><i class="fas fa-trash"></i> Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <?php if (empty($faqs)): ?>
                    <p>No hay preguntas frecuentes para esta categoría.</p>
                <?php endif; ?>
            </section>
        <?php else: ?>
            <section class="gestion-section">
                <h2>Categorías principales</h2>
                <table class="gestionar-table">
                    <tr><th class="gestionar-th">Título</th><th class="gestionar-th">Descripción</th><th class="gestionar-th">Icono</th><th class="gestionar-th">Tipo</th><th class="gestionar-th">Acciones</th></tr>
                    <?php foreach ($categorias as $cat): ?>
                        <tr>
                            <td class="gestionar-td"><?= htmlspecialchars($cat->getTitulo()) ?></td> <!-- Mostramos el título de la categoría -->
                            <td class="gestionar-td"><?= htmlspecialchars($cat->getDescripcion()) ?></td> <!-- Mostramos la descripción de la categoría -->
                            <td class="gestionar-td"><?= htmlspecialchars($cat->getIcono()) ?></td> <!-- Mostramos el icono de la categoría -->
                            <td class="gestionar-td"><?= $cat->getIdMadre() ? 'Subcategoría' : 'Principal' ?></td> <!-- Mostramos si es una categoría principal o una subcategoría según tenga id_madre o no -->
                            <td class="gestionar-td">
                                <a href="?id=<?= $cat->getIdCategoria() ?>" class="viewbutton"><i class="fas fa-eye"></i> Ver</a> <!-- Enlace para ver la categoría, redirige a la misma página con el id de la categoría para mostrar su contenido. -->
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
            <!-- Solo mostramos el formulario de bloque si estamos editando un bloque existente o creando uno nuevo dentro de una subcategoría. -->
            <section class="gestionar-section">
                <h3><?= $editar_bloque ? 'Editar' : 'Nuevo' ?> Bloque</h3> <!-- Formulario para crear o editar un bloque de contenido dentro de una subcategoría. -->
                <form method="post" action="?id=<?= $categoryId ?>" class="gestionar-form"> <!-- El formulario se envía a la misma página con el id de la categoría actual para gestionar su contenido. -->
                    <input type="hidden" name="accion" value="guardar_bloque">
                    <?php if ($editar_bloque): ?> <!-- Si estamos editando un bloque existente, guardamos su id oculta. -->
                        <input type="hidden" name="id_bloque" value="<?= $editar_bloque->getIdBloque() ?>"> 
                        <!-- Guardamos el id del bloque que estamos editando, se actualiza ese bloque en lugar de crear uno nuevo. -->
                    <?php endif; ?>
                    <label class="gestionar-label">Título: <input type="text" name="titulo" class="gestionar-input" value="<?= $editar_bloque ? htmlspecialchars($editar_bloque->getTitulo()) : '' ?>" required></label>
                    <!-- Campo para el título del bloque, requerido. -->
                    <label class="gestionar-label">Subtítulo: <input type="text" name="subtitulo" class="gestionar-input" value="<?= $editar_bloque ? htmlspecialchars($editar_bloque->getSubtitulo()) : '' ?>"></label>
                    <!-- Campo para el subtítulo del bloque, opcional. -->
                    <label class="gestionar-label">Contenido: <textarea name="contenido" class="gestionar-textarea" required><?= $editar_bloque ? htmlspecialchars($editar_bloque->getContenido()) : '' ?></textarea></label>
                    <!-- Campo para el contenido del bloque, requerido. -->
                    <label class="gestionar-label">Orden: <input type="number" name="orden" class="gestionar-input" value="<?= $editar_bloque ? $editar_bloque->getOrden() : '' ?>" required></label>
                    <!-- Campo para el orden del bloque, requerido. -->
                    <?php if ($editar_bloque): ?>
                        <input type="hidden" name="id_categoria" value="<?= $editar_bloque->getIdCategoria() ?>"> 
                        <!-- Guardamos el id de la categoría a la que pertenece el bloque que estamos editando para mantener la relación. -->
                    <?php else: ?>
                        <input type="hidden" name="id_categoria" value="<?= $categoryId ?>"> 
                        <!-- Si es un bloque nuevo, asignamos el id de la categoría actual para relacionarlo correctamente. -->
                    <?php endif; ?>
                    <button type="submit" class="savebutton"><i class="fa-solid fa-floppy-disk"></i>Guardar</button> <!-- Botón para guardar el bloque de contenido. -->
                    <a href="?id=<?= $categoryId ?>" class="deletebutton"><i class="fa-solid fa-times"></i> Cancelar</a> 
                    <!-- Enlace para cancelar la acción y volver a la vista de gestión de la categoría sin guardar cambios. -->
                </form>
            </section>
        <?php endif; ?>

        <?php if ($editar_faq || (isset($_GET['nuevo_faq']) && $currentCategory)): ?> 
            <!-- Formulario para crear o editar una FAQ -->
            <section class="gestionar-section">
                <h3><?= $editar_faq ? 'Editar' : 'Nueva' ?> Pregunta Frecuente</h3>
                <form method="post" action="?id=<?= $categoryId ?>" class="gestionar-form">
                    <input type="hidden" name="accion" value="guardar_faq">
                    <?php if ($editar_faq): ?>
                        <input type="hidden" name="id_faq" value="<?= $editar_faq->getIdFaq() ?>">
                    <?php endif; ?>
                    <label class="gestionar-label">Pregunta: <input type="text" name="pregunta" class="gestionar-input" value="<?= $editar_faq ? htmlspecialchars($editar_faq->getPregunta()) : '' ?>" required></label>
                    <label class="gestionar-label">Respuesta: <textarea name="respuesta" class="gestionar-textarea" required><?= $editar_faq ? htmlspecialchars($editar_faq->getRespuesta()) : '' ?></textarea></label>
                    <?php if ($editar_faq): ?>
                        <input type="hidden" name="id_categoria" value="<?= $editar_faq->getIdCategoria() ?>">
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
