<?php
session_start();
$base = '/reto4-medicosdelmundo/';

// Acceso para administradoras y orientadoras (id_rol === 2, 3)
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['id_rol']) || !in_array(intval($_SESSION['id_rol']), [2, 3], true)) {
    header('Location: /reto4-medicosdelmundo/signin.php');
    exit();
}

require_once '../conexion.php';
require_once '../clases/Categoria.php';
require_once '../clases/Faq.php';

$db = new Database();
$conn = $db->conectar();

// Procesar formulario si se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];

        // Eliminar una FAQ
        if ($accion === 'eliminar_faq' && isset($_POST['id_faq'])) {
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
        }

        header('Location: ' . $base . 'Vistadmin/gestionar_faqs.php');
        exit();
    }
}

// Cargar todas las FAQs
$todasLasFaqs = Faq::obtenerTodas($conn);
$categorias = Categoria::obtenerTodas($conn);

// Cargar FAQ a editar si existe
$editar_faq = null;
if (isset($_GET['editar_faq'])) {
    $editar_faq = Faq::obtenerPorId($conn, $_GET['editar_faq']);
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Gestionar FAQs</title>
    <link rel="stylesheet" href="<?= $base ?>estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/png" href="<?= $base ?>Imagenes/Logoreal.png">
</head>
<body>
    <?php include '../barrasNavegacion/headeradmin.php'; ?>
    <main class="gestionar-main">
        <h1>Gestionar Preguntas Frecuentes (FAQs)</h1>

        <section class="gestion-section">
            <a href="<?= $base ?>Vistadmin/Menu.php" class="menubutton"><i class="fa-solid fa-arrow-left"></i>Volver al menú</a>
            <a href="?nueva_faq=1" class="menubutton"><i class="fa-solid fa-plus"></i>Añadir Nueva FAQ</a>
        </section>

        <!-- Tabla de todas las FAQs -->
        <section class="gestion-section">
            <h2>Todas las Preguntas Frecuentes</h2>
            <table class="gestionar-table">
                <tr>
                    <th class="gestionar-th">Categoría</th>
                    <th class="gestionar-th">Pregunta</th>
                    <th class="gestionar-th">Respuesta</th>
                    <th class="gestionar-th">Acciones</th>
                </tr>
                <?php foreach ($todasLasFaqs as $faq): ?>
                    <?php 
                        $cat = Categoria::obtenerPorId($conn, $faq->getIdCategoria());
                        $nombreCategoria = $cat ? htmlspecialchars($cat->getTitulo()) : 'Desconocida';
                    ?>
                    <tr>
                        <td class="gestionar-td"><?= $nombreCategoria ?></td>
                        <td class="gestionar-td"><?= htmlspecialchars($faq->getPregunta()) ?></td>
                        <td class="gestionar-td"><?= htmlspecialchars(substr($faq->getRespuesta(), 0, 100)) ?>...</td>
                        <td class="gestionar-td">
                            <a href="?editar_faq=<?= $faq->getIdFaq() ?>" class="editbutton"><i class="fas fa-pencil"></i> Editar</a>
                            <form method="post" class="gestionar-form-inline">
                                <input type="hidden" name="accion" value="eliminar_faq">
                                <input type="hidden" name="id_faq" value="<?= $faq->getIdFaq() ?>">
                                <button type="submit" class="deletebutton" onclick="return confirm('¿Eliminar FAQ?')"><i class="fas fa-trash"></i> Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php if (empty($todasLasFaqs)): ?>
                <p>No hay preguntas frecuentes registradas.</p>
            <?php endif; ?>
        </section>

        <!-- Formulario para crear o editar FAQ -->
        <?php if ($editar_faq || isset($_GET['nueva_faq'])): ?>
            <section class="gestionar-section">
                <h3><?= $editar_faq ? 'Editar' : 'Nueva' ?> Pregunta Frecuente</h3>
                <form method="post" class="gestionar-form">
                    <input type="hidden" name="accion" value="guardar_faq">
                    
                    <?php if ($editar_faq): ?>
                        <input type="hidden" name="id_faq" value="<?= $editar_faq->getIdFaq() ?>">
                    <?php endif; ?>

                    <label class="gestionar-label">
                        Categoría:
                        <select name="id_categoria" class="gestionar-input" required>
                            <option value="">Seleccionar categoría</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= $cat->getIdCategoria() ?>" 
                                    <?= ($editar_faq && $editar_faq->getIdCategoria() == $cat->getIdCategoria()) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat->getTitulo()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>

                    <label class="gestionar-label">
                        Pregunta:
                        <input type="text" name="pregunta" class="gestionar-input" 
                            value="<?= $editar_faq ? htmlspecialchars($editar_faq->getPregunta()) : '' ?>" required>
                    </label>

                    <label class="gestionar-label">
                        Respuesta:
                        <textarea name="respuesta" class="gestionar-textarea" required><?= $editar_faq ? htmlspecialchars($editar_faq->getRespuesta()) : '' ?></textarea>
                    </label>

                    <button type="submit" class="savebutton"><i class="fa-solid fa-floppy-disk"></i>Guardar</button>
                    <a href="<?= $base ?>Vistadmin/gestionar_faqs.php" class="deletebutton"><i class="fa-solid fa-times"></i> Cancelar</a>
                </form>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>
