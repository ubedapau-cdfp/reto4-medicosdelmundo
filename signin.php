<?php // Inicio del apartado PHP
$base = '/reto4-medicosdelmundo/'; // valor $base equivale a la ruta absoluta para su uso en la página
?> <!-- Cierre del apartado PHP -->
<!DOCTYPE html> <!-- Tipo de documento HTML -->
<html lang="es"> <!-- Inicio del HTML -->
<head> <!--  -->
    <meta charset="UTF-8"> <!-- Juego de carácteres para visualización correcta. Tipo UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Tamaño y forma del viewport -->
    <link rel='stylesheet' href='estilos.css'> <!-- Conexión con el CSS -->
    <link rel="icon" type="image/png" href="<?= $base ?>Imagenes/Logoreal.png"> <!-- Icono para la parte superior de la página -->
    <title>Login</title> <!-- Título para la parte superior de la página -->
</head> <!-- Cierre del head -->
<body> <!-- Inicio del body -->
    <section class="loginMenu"> <!-- Inicio del section loginMenu -->
        <section class="ladoIzqdo"> <!-- Inicio del section ladoIzqdo -->
            <p></p> <!-- Separador -->
        </section> <!-- Cierre del section ladoIzqdo -->
        <section class="ladoDcho"> <!-- Inicio del section ladoDcho -->
            <section class="menuform"> <!-- Inicio del section menuform -->
                <h1>Inicio de Sesión</h1> <!-- Párrafo de texto estilo h1 que sirve como título -->
                &nbsp; <!-- Non-Breaking Space. Proporciona saltos de línea, con un espacio. -->
                <form action='' method='POST'> <!-- Inicio de formulario con método POST -->
                    <label for='usuaria'>Nombre:</label> <!-- Texto para identificar el apartado 'Nombre' -->
                    <input type='text' name='usuaria' id='usuaria' size='50' placeholder=' Usuaria01' required> <!-- Campo de texto del apartado 'Nombre' obligatorio -->
                    <p></p> <!-- Separador -->
                    <label for='contrasena'>Contraseña:</label> <!-- Texto para identificar el apartado 'Contrasena' -->
                    <input type='password' name='contrasena' size='46' id='contrasena' placeholder=' •••••••' required> <!-- Campo de texto del apartado 'Contrasena' obligatorio -->
                    <p></p> <!-- Separador -->
                    <button type='submit'>Iniciar Sesión</button> <!-- Botón para iniciar sesión -->
                    <button type='submit'><a href='/reto4-medicosdelmundo/home/home.php'>Volver a Inicio</a></button> <!-- Botón con enlace que redirecciona a home.php -->
                    <p></p> <!-- Separador -->
                    <?php require_once 'loginProceso.php' ?> <!-- Proceso del login importado mediante PHP -->
                </form> <!-- Cierre del formulario -->
            </section> <!-- Cierre del section menuform -->
        </section> <!-- Cierre del section ladoDcho -->
    </section> <!-- Cierre del section loginMenu -->
</body> <!-- Cierre del body -->
</html> <!-- Cierre del HTML -->