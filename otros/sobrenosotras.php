<?php // Inicio del apartado PHP
session_start(); // Iniciamos sesion
$base = '/reto4-medicosdelmundo/'; // Ruta base
?> <!-- Fin del apartado PHP -->
<!DOCTYPE html> <!-- Documento de tipo HTML -->
<html lang="es"> <!-- Idioma español -->
<head> 
<meta charset="UTF-8"> <!-- Codificación de caracteres UTF-8 -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sobre Nosotras</title>
<link rel="icon" type="image/png" href="<?= $base ?>Imagenes/Logoreal.png"> <!-- Favicon -->
<link rel="stylesheet" href="../estilos.css"> <!-- CSS -->
</head>
<body> <!-- Inicio del body -->
<?php include '../barrasNavegacion/header.php'; ?> <!-- Incluimos el header -->
<p>
<section class="contenidos">
    <section class="sobre-nosotras-content">
        <h1>Sobre Médicos del Mundo</h1> <!-- Título -->
        <section class="sobre-nosotras-texto"> <!-- Texto explicatorio -->
            <p>Médicos del Mundo es una organización internacional humanitaria independiente que lucha por el derecho universal a la salud. Desde 1986, trabajamos en más de 80 países proporcionando atención médica de calidad a las poblaciones más vulnerables, especialmente en contextos de crisis humanitarias, conflictos armados y situaciones de pobreza extrema.</p>
            
            <p><strong>Nuestra Misión:</strong> Proporcionar atención médica gratuita y de calidad a personas en situaciones de vulnerabilidad, denunciar las violaciones de los derechos humanos relacionados con la salud y promover políticas que garanticen el acceso equitativo a la atención sanitaria para todos.</p>
            
            <p><strong>Nuestros Valores:</strong> Solidaridad, independencia, compromiso con los derechos humanos, profesionalismo y transparencia. Creemos en la acción directa sobre el terreno, combinada con el trabajo de incidencia política para cambiar las leyes y políticas injustas.</p>
            
            <p>En España y en todo el mundo, Médicos del Mundo desarrolla programas de salud comunitaria, atención a migrantes, lucha contra la pobreza energética, apoyo a personas sin hogar y respuesta a emergencias sanitarias. Nuestro trabajo se basa en la evidencia científica y en el respeto a la dignidad de cada persona.</p>
        </section>
        
        <section class="sobre-nosotras-enlace">
            <a href="https://www.medicosdelmundo.org/" target="_blank" class="button-web-mdm"> <!-- Enlace al sitio web oficial de Médicos del Mundo -->
                <i class="fas fa-external-link-alt"></i> Visitar Sitio Web Oficial             <!-- Mediante click del botón -->
            </a>
        </section>
    </section>
</section>
<?php include '../barrasNavegacion/footer.php'; ?> <!-- Incluimos el footer -->

</body> <!-- Fin del body -->
</html> <!-- Fin del documento HTML -->