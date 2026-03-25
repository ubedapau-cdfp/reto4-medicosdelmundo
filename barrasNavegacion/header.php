<?php
$base = '/reto4-medicosdelmundo/';
?>
<header>
    <a href="<?= $base ?>home/home.php" class="logo">
        <img src="<?= $base ?>Imagenes/Logoreal.png" alt="Logo">
    </a>

    <nav>
        <ul>
            <li class="dropdown">
                <a href="#">Contratos ▾</a>
                <ul>
                    <li><a href="<?= $base ?>contratos/relacionlaboral.php">Requisitos para la Relación Laboral</a></li>
                    <li><a href="<?= $base ?>contratos/normativayderechos.php">Jerarquía normativa y derechos</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="#">Elementos y Condiciones del Contrato ▾</a>
                <ul>
                    <li><a href="<?= $base ?>elementosYcondiciones/elementossustanciales.php">Elementos Sustanciales</a></li>
                    <li><a href="<?= $base ?>elementosYcondiciones/elementosimportantes.php">Elementos Importantes pero No Imprescindibles</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="#">Proceso de Contratación y Requisitos ▾</a>
                <ul>
                    <li><a href="<?= $base ?>procesoDeContratacionRequisitos/obligacionesempresa.php">Obligaciones de la Empresa</a></li>
                    <li><a href="<?= $base ?>procesoDeContratacionRequisitos/requisitostrabajadora.php">Requisitos de la Trabajadora</a></li>
                    <li><a href="<?= $base ?>procesoDeContratacionRequisitos/nacionalidad.php">Nacionalidad</a></li>
                    <li><a href="<?= $base ?>procesoDeContratacionRequisitos/relacionesexcluidasyespeciales.php">Relaciones Excluidas y Especiales</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="#">Relación Laboral ▾</a>
                <ul>
                    <li><a href="<?= $base ?>RelacionLaboral/definicionyrequisitos.php">Definición y Requisitos</a></li>
                    <li><a href="<?= $base ?>RelacionLaboral/principiosgeneralesderecholaboral.php">Principios Generales del Derecho Laboral</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <section class="enlaces-derecha">
        <a href="<?= $base ?>otros/sobrenosotras.php">Sobre nosotras</a>
        <a href="<?= $base ?>otros/preguntasfrecuentes.php">Preguntas frecuentes</a>
    </section>
    <section>
        <button class='loginbutton'><a href="<?= $base ?>signin.php">Login</a></button>
    </section>
</header>