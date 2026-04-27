<?php // Inicio del apartado PHP
$base = '/reto4-medicosdelmundo/'; // valor $base equivale a la ruta absoluta para su uso en la página
?> <!-- Cierre del apartado PHP -->
<!DOCTYPE html> <!-- Tipo de documento HTML -->
<html lang="es"> <!-- Inicio del HTML -->
<head> <!-- Inicio del head -->
<meta charset="UTF-8"> <!-- Juego de carácteres para visualización correcta. Tipo UTF-8 -->
<meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Tamaño y forma del viewport -->
<title>Jerarquía normativa y derechos</title> <!-- Título para la parte superior -->
<link rel="icon" type="image/png" href="<?= $base ?>Imagenes/Logoreal.png"> <!-- Icono para la parte superior -->
<link rel="stylesheet" href="../estilos.css"> <!-- Conexión con el CSS -->
</head> <!-- Cierre del head -->
<body> <!-- Inicio del body -->
<?php include '../barrasNavegacion\header.php'; ?> <!-- Header importado externamente mediante PHP -->
<p></p> <!-- Separador -->
<section class="contenidos"> <!-- Inicio del section contenidos -->
    <p><b>Jerarquía normativa y Derechos</b></p> <!-- Párrafo de texto, título en negrita -->
    <p>Un contrato no puede contener cualquier cosa. Por ejemplo:</p> <!-- Párrafo de texto -->
    &nbsp; <!-- Non-Breaking Space. Proporciona saltos de línea, con un espacio. -->
    <p><b class="subtitulos">· Principio de Irrenunciabilidad de Derechos</b></p> <!-- Párrafo de texto, con negrita clase subtitulos. Subapartados -->
    <p>Aunque una misma quisiera, una empleada no puede firmar la renuncia de sus vacaciones, o a cobrar menos del salario mínimo legal.</p> <!-- Párrafo de texto -->
    <p>Este pacto sería nulo.</p> <!-- Párrafo de texto -->
    &nbsp; <!-- Non-Breaking Space. Proporciona saltos de línea, con un espacio. -->
    <p><b class="subtitulos">· Principio de Norma Mínima</b></p> <!-- Párrafo de texto, con negrita clase subtitulos. Subapartados -->
    <p>Las leyes marcan una base (ej. 30 días de vacaciones) que el contrato sólo puede mejorar (ej. 35 días), pero nunca empeorar (ej. 20 días).</p> <!-- Párrafo de texto -->
    <p>Este pacto sería nulo.</p> <!-- Párrafo de texto -->
    &nbsp; <!-- Non-Breaking Space. Proporciona saltos de línea, con un espacio. -->
    <p><strong><i>Ejemplo:</strong></i> Si el SMI es de 1.221€ (2026), una empresa no puede pagarte 900€, ni aunque tú aceptes por necesidad.</p> <!-- Párrafo de texto, en negrita y en italic para el ejemplo, luego normal para el resto del ejemplo. -->
    &nbsp; <!-- Non-Breaking Space. Proporciona saltos de línea, con un espacio. -->
    <p>Revisa los diferentes apartados que tenemos seleccionando uno de los siguientes botones:</p> <!-- Párrafo de texto -->
    &nbsp; <!-- Non-Breaking Space. Proporciona saltos de línea, con un espacio. -->
    <section class="botonera"> <!-- Inicio del section botonera -->
    <a href="../contratar.php" class="button">Contratar</a><a href="../sercontratada.php" class="button">Ser Contratada</a><a href="#" class="button">Indemnización</a>
    </section> <!-- Cierre del section botonera -->
</section> <!-- Cierre del section contenidos -->
<?php include '../barrasNavegacion\footer.php'; ?> <!-- Footer importado externamente mediante PHP -->
</body> <!-- Cierre del body -->
</html> <!-- Cierre del HTML -->