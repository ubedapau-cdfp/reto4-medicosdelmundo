<?php // Inicio del apartado PHP
$base = '/reto4-medicosdelmundo/'; // Ruta base para enlaces y recursos
require_once '../conexion.php'; // Incluir el archivo de conexión a la base de datos

$db = new Database(); // Crear una instancia de la clase Database
$conn = $db->conectar(); // Establecer la conexión a la base de datos

// Obtener todas las preguntas frecuentes agrupadas por categoría
$sql = "SELECT f.id_faq, f.pregunta, f.respuesta, f.id_categoria, c.titulo as categoria_titulo
        FROM FAQ f
        LEFT JOIN CATEGORIA c ON f.id_categoria = c.id_categoria
        ORDER BY c.titulo ASC, f.id_faq ASC";

$faqs = [];
try {
    $stmt = $conn->prepare($sql); // Preparar la consulta SQL
    $stmt->execute(); // Ejecutar la consulta
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // Iterar sobre los resultados de la consulta
        $categoria = $row['categoria_titulo'] ?: 'Sin categoría'; // Usar 'Sin categoría' si no hay título de categoría
        if (!isset($faqs[$categoria])) { // Verificar si la categoría ya existe en el array, si no, inicializarla
            $faqs[$categoria] = []; // Inicializar el array para la categoría si no existe
        }
        $faqs[$categoria][] = [ // Agregar la pregunta frecuente al array de la categoría correspondiente
            'pregunta' => $row['pregunta'], // Guardar la pregunta frecuente
            'respuesta' => $row['respuesta'] // Guardar la respuesta a la pregunta frecuente
        ];
    }
} catch (PDOException $e) { 
    echo "Error al obtener preguntas frecuentes: " . $e->getMessage(); // Mensaje de error
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
<?php include '../barrasNavegacion/header.php'; ?> <!-- Inclusión del header en la página -->
<section class="contenidos">
    <h1>Preguntas Frecuentes</h1>
    <p>Encuentra respuestas a las preguntas más comunes sobre tus derechos laborales.</p>
    
    <?php if (empty($faqs)): ?> <!-- Si no hay preguntas frecuentes, mostrar mensaje -->
        <p>No hay preguntas frecuentes disponibles en este momento.</p>
    <?php else: ?>
        <?php foreach ($faqs as $categoria => $items): ?> <!-- Iterar sobre cada categoría de preguntas frecuentes -->
            <section class="faq-categoria">
                <h2 class="categoria-titulo"><?= htmlspecialchars($categoria) ?></h2> <!-- Título de la categoría de preguntas frecuentes -->
                <section class="faq-items">
                    <?php foreach ($items as $item): ?> <!-- Iterar sobre cada pregunta frecuente dentro de la categoría -->
                        <section class="faq-item">
                            <p class="faq-pregunta"><b>P: <?= htmlspecialchars($item['pregunta']) ?></b></p> <!-- Pregunta frecuente en negrita -->
                            <p class="faq-respuesta"><b>R:</b> <?= htmlspecialchars($item['respuesta']) ?></p> <!-- Respuesta a la pregunta frecuente -->
                        </section>
                    <?php endforeach; ?>
                </section>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
<?php include '../barrasNavegacion/footer.php'; ?> <!-- Inclusión del footer en la página -->
</body>
</html>
