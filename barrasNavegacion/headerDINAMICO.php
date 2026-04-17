<?php // Inicio del apartado PHP
    // Incluimos tu archivo de conexión existente
        include_once __DIR__ . "/../conexion.php";
    if (session_status() === PHP_SESSION_NONE) session_start();
    $base = '/reto4-medicosdelmundo/'; // Valor $base equivale a la ruta absoluta para su uso en la página

    //Llamamos a la función para obtener las categorías principales
    $categorias_madre = obtenerCategoriasMadre($conn);
?> <!-- Cierre del apartado PHP -->

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<header> <!-- Inicio del header -->
    <a href="<?= $base ?>home/home.php" class="logo"> <!-- Inicio del enlace, que redirecciona al home.php -->
        <img src="<?= $base ?>Imagenes/Logoreal.png" alt="Logo"> <!-- Imagen del logo de la ONG -->
    </a> <!-- Cierre del enlace -->

    <nav>
        <ul>
            <?php 
            // 3. Recorremos las categorías madre
            foreach ($categorias_madre as $madre): 
                // Llamamos a la función de subcategorías pasando el ID de la madre actual
                $subcategorias = obtenerSubcategorias($conn, $madre['id_categoria']);
            ?>
                <li class="dropdown">
                    <a href="#">
                        <i class="fa-solid <?= $madre['icono'] ?>"></i> 
                        <?= $madre['titulo'] ?> ▾
                    </a>
                    
                    <?php if (count($subcategorias) > 0): ?>
                        <ul>
                            <?php foreach ($subcategorias as $hija): ?>
                                <li>
                                    <a href="<?= $base ?>contenidos.php?id=<?= $hija['id_categoria'] ?>">
                                        <i class="fa-solid <?= $hija['icono'] ?>"></i>
                                        <?= $hija['titulo'] ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <section class="enlaces-derecha"> <!-- Inicio del section enlaces-derecha -->
        <a href="<?= $base ?>otros/sobrenosotras.php"><i class="fa-solid fa-users"></i>Sobre nosotras</a> <!-- Enlace que redirecciona a sobrenosotras.php -->
        <a href="<?= $base ?>otros/preguntasfrecuentes.php"><i class="fa-solid fa-circle-question"></i>FAQs</a> <!-- Enlace que redirecciona a preguntasfrecuentes.php -->
    </section> <!-- Cierre del section enlaces-derecha -->

    <section class="admin-session"> <!-- Inicio del section sin nombre -->
        <?php
            $page = basename($_SERVER['PHP_SELF']);// Obtener el nombre del archivo PHP actual pero solo la ultima parte

            if (isset($_SESSION['usuario_nombre'])) {// Verificar si la variable de sesión 'usuario_nombre' está establecida, lo que indica que el usuario ha iniciado sesión
                $nombre = $_SESSION['usuario_nombre'];// Obtener el nombre del usuario desde la variable de sesión
                echo "<span class='admin-name'>Hola, " . $nombre . "</span>";// Mostrar un mensaje de bienvenida con el nombre del usuario
                echo "<a class='logout-button' href='" . $base . "logout.php'>Cerrar sesión</a>";// Mostrar un enlace para cerrar sesión que redirecciona a logout.php
            } else {
                echo "<button class='loginbutton'>";
                echo "<a href='" . $base . "signin.php'>"."<i class=\"fa-solid fa-user\"></i>"."Login</a>"; // Botón que redirecciona al signin.php
                echo "</button>";
            }
        ?>
    </section> <!-- Cierre del section sin nombre -->
</header> <!-- Cierre del header -->