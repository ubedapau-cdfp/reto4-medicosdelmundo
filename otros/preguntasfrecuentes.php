<?php
$base = '/reto4-medicosdelmundo/';
require_once '../conexion.php';

$db = new Database();
$conn = $db->conectar();

// Obtener todas las preguntas frecuentes agrupadas por categoría
$sql = "SELECT f.id_faq, f.pregunta, f.respuesta, f.id_categoria, c.titulo as categoria_titulo
        FROM FAQ f
        LEFT JOIN CATEGORIA c ON f.id_categoria = c.id_categoria
        ORDER BY c.titulo ASC, f.id_faq ASC";

$faqs = [];
try {
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $categoria = $row['categoria_titulo'] ?: 'Sin categoría';
        if (!isset($faqs[$categoria])) {
            $faqs[$categoria] = [];
        }
        $faqs[$categoria][] = [
            'pregunta' => $row['pregunta'],
            'respuesta' => $row['respuesta']
        ];
    }
} catch (PDOException $e) {
    echo "Error al obtener preguntas frecuentes: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Preguntas Frecuentes</title>
<link rel="icon" type="image/png" href="<?= $base ?>Imagenes/Logoreal.png">
<link rel="stylesheet" href="../estilos.css">
</head>
<body>
<?php include '../barrasNavegacion/header.php'; ?>
<section class="contenidos">
    <h1>Preguntas Frecuentes</h1>
    <p>Encuentra respuestas a las preguntas más comunes sobre tus derechos laborales.</p>
    
    <?php if (empty($faqs)): ?>
        <p>No hay preguntas frecuentes disponibles en este momento.</p>
    <?php else: ?>
        <?php foreach ($faqs as $categoria => $items): ?>
            <section class="faq-categoria">
                <h2 class="categoria-titulo"><?= htmlspecialchars($categoria) ?></h2>
                <section class="faq-items">
                    <?php foreach ($items as $item): ?>
                        <section class="faq-item">
                            <p class="faq-pregunta"><b>P: <?= htmlspecialchars($item['pregunta']) ?></b></p>
                            <p class="faq-respuesta"><b>R:</b> <?= htmlspecialchars($item['respuesta']) ?></p>
                        </section>
                    <?php endforeach; ?>
                </section>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
<?php include '../barrasNavegacion/footer.php'; ?>
</body>
</html>
