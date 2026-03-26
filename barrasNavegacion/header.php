<?php // Inicio del apartado PHP
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
    <section> <!-- Inicio del section sin nombre -->
        <button class='loginbutton'><a href="<?= $base ?>signin.php">Login</a></button> <!-- Botón que redirecciona al signin.php -->
    </section> <!-- Cierre del section sin nombre -->
</header> <!-- Cierre del header -->