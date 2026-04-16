<?php // Inicio del apartado PHP
if (session_status() === PHP_SESSION_NONE) session_start();
$base = '/reto4-medicosdelmundo/'; // Valor $base equivale a la ruta absoluta para su uso en la página
?> <!-- Cierre del apartado PHP -->
<header> <!-- Inicio del header -->
    <a href="<?= $base ?>home/home.php" class="logo"> <!-- Inicio del enlace, que redirecciona al home.php -->
        <img src="<?= $base ?>Imagenes/Logoreal.png" alt="Logo"> <!-- Imagen del logo de la ONG -->
    </a> <!-- Cierre del enlace -->

    <nav> <!-- Inicio del nav -->
        <ul> <!-- Inicio de la lista desordenada general -->
            <li class="dropdown"> <!-- Inicio del ítem de lista class dropdown -->
                <a href="#">Contratos ▾</a> <!-- Enlace sin redirección que muestra los apartados, título del apartado -->
                <ul> <!-- Inicio de lista desordenada del class dropdown --> 
                    <li><a href="<?= $base ?>contratos/relacionlaboral.php">Requisitos para la Relación Laboral</a></li> <!-- Ítem de lista con enlace que redirecciona a relacionlaboral.php -->
                    <li><a href="<?= $base ?>contratos/normativayderechos.php">Jerarquía normativa y derechos</a></li> <!-- Ítem de lista con enlace que redirecciona a normativayderechos.php -->
                </ul> <!-- Cierre de lista desordenada del class dropdown -->
            </li> <!-- Cierre de ítem de lista del class dropdown -->

            <li class="dropdown"> <!-- Inicio del ítem de lista class dropdown -->
                <a href="#">Elementos y Condiciones del Contrato ▾</a> <!-- Enlace sin redirección que muestra los apartados, título del apartado -->
                <ul> <!-- Inicio de lista desordenada del class dropdown --> 
                    <li><a href="<?= $base ?>elementosYcondiciones/elementossustanciales.php">Elementos Sustanciales</a></li> <!-- Ítem de lista con enlace que redirecciona a elementossustanciales.php -->
                    <li><a href="<?= $base ?>elementosYcondiciones/elementosimportantes.php">Elementos Importantes pero No Imprescindibles</a></li> <!-- Ítem de lista con enlace que redirecciona a elementosimportantes.php -->
                </ul> <!-- Cierre de lista desordenada del class dropdown -->
            </li> <!-- Cierre de ítem de lista del class dropdown -->

            <li class="dropdown"> <!-- Inicio del ítem de lista class dropdown -->
                <a href="#">Proceso de Contratación y Requisitos ▾</a> <!-- Enlace sin redirección que muestra los apartados, título del apartado -->
                <ul> <!-- Inicio de lista desordenada del class dropdown --> 
                    <li><a href="<?= $base ?>procesoDeContratacionRequisitos/obligacionesempresa.php">Obligaciones de la Empresa</a></li> <!-- Ítem de lista con enlace que redirecciona a obligacionesempresa.php -->
                    <li><a href="<?= $base ?>procesoDeContratacionRequisitos/requisitostrabajadora.php">Requisitos de la Trabajadora</a></li> <!-- Ítem de lista con enlace que redirecciona a requisitostrabajadora.php -->
                    <li><a href="<?= $base ?>procesoDeContratacionRequisitos/nacionalidad.php">Nacionalidad</a></li> <!-- Ítem de lista con enlace que redirecciona a nacionalidad.php -->
                    <li><a href="<?= $base ?>procesoDeContratacionRequisitos/relacionesexcluidasyespeciales.php">Relaciones Excluidas y Especiales</a></li> <!-- Ítem de lista con enlace que redirecciona a relacionesexcluidasyespeciales.php -->
                </ul> <!-- Cierre de lista desordenada del class dropdown -->
            </li> <!-- Cierre de ítem de lista del class dropdown -->

            <li class="dropdown"> <!-- Inicio del ítem de lista class dropdown -->
                <a href="#">Relación Laboral ▾</a> <!-- Enlace sin redirección que muestra los apartados, título del apartado -->
                <ul> <!-- Inicio de lista desordenada del class dropdown --> 
                    <li><a href="<?= $base ?>RelacionLaboral/definicionyrequisitos.php">Definición y Requisitos</a></li> <!-- Ítem de lista con enlace que redirecciona a definicionyrequisitos.php -->
                    <li><a href="<?= $base ?>RelacionLaboral/principiosgeneralesderecholaboral.php">Principios Generales del Derecho Laboral</a></li> <!-- Ítem de lista con enlace que redirecciona a principiosgeneralesderecholaboral.php -->
                </ul> <!-- Cierre de lista desordenada del class dropdown -->
            </li> <!-- Cierre de ítem de lista del class dropdown -->
        </ul> <!-- Cierre de lista desordenada general -->
    </nav> <!-- Cierre del nav -->

    <section class="enlaces-derecha"> <!-- Inicio del section enlaces-derecha -->
        <a href="<?= $base ?>otros/sobrenosotras.php">Sobre nosotras</a> <!-- Enlace que redirecciona a sobrenosotras.php -->
        <a href="<?= $base ?>otros/preguntasfrecuentes.php">Preguntas frecuentes</a> <!-- Enlace que redirecciona a preguntasfrecuentes.php -->
    </section> <!-- Cierre del section enlaces-derecha -->
    <section class="admin-session"> <!-- Inicio del section sin nombre -->
<?php
$page = basename($_SERVER['PHP_SELF']);// Obtener el nombre del archivo PHP actual pero solo la ultima parte

if (isset($_SESSION['usuario_nombre'])) {// Verificar si la variable de sesión 'usuario_nombre' está establecida, lo que indica que el usuario ha iniciado sesión
    $nombre = $_SESSION['usuario_nombre'];// Obtener el nombre del usuario desde la variable de sesión
    echo "<span class='admin-name'>Hola, " . $nombre . "</span>";// Mostrar un mensaje de bienvenida con el nombre del usuario
    echo "<a class='logout-button' href='" . $base . "logout.php'>Cerrar sesión</a>";// Mostrar un enlace para cerrar sesión que redirecciona a logout.php
} else {
    echo "<button class='loginbutton'>";// Mostrar un botón de inicio de sesión
    echo "<a href='" . $base . "signin.php'>Login</a>";// Mostrar un enlace dentro del botón que redirecciona a signin.php
    echo "</button>";// Cerrar el botón de inicio de sesión
}
?>
    </section> <!-- Cierre del section sin nombre -->
</header> <!-- Cierre del header -->