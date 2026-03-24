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
                    <li><a href="#">Elementos del contrato</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="#">Elementos y Condiciones del Contrato ▾</a>
                <ul>
                    <li><a href="#">Elementos sustanciales</a></li>
                    <li><a href="#">Elementos importantes pero no imprescindibles</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="#">Proceso de Contratación y Requisitos ▾</a>
                <ul>
                    <li><a href="#">Obligaciones de la Empresa</a></li>
                    <li><a href="#">Requisitos de la Trabajadora</a></li>
                    <li><a href="#">Nacionalidad</a></li>
                    <li><a href="#">Relaciones Excluidas y Especiales</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="#">Relación Laboral ▾</a>
                <ul>
                    <li><a href="#">Definición y Requisitos</a></li>
                    <li><a href="#">Principios Generales del Derecho Laboral</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <section class="enlaces-derecha">
        <a href="#" class="googoo">Sobre nosotros</a>
        <a href="#">Preguntas frecuentes</a>
    </section>
    <section>
        <button class='loginbutton'><a href="<?= $base ?>signin.php">Login</a></button>
    </section>
</header>