<?php // Inicio del apartado PHP
    // Incluimos la conexión POO y obtenemos las categorías madre
    include_once __DIR__ . "/../conexion.php";
    include_once __DIR__ . "/../clases/Categoria.php";
    if (session_status() === PHP_SESSION_NONE) session_start();
    $base = '/reto4-medicosdelmundo/'; // Valor $base equivale a la ruta absoluta para su uso en la página

    $database = new Database();
    $conn = $database->conectar();
    $categorias_madre = Categoria::obtenerCategoriasMadre($conn);
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
                // Obtener subcategorías usando la clase Categoria
                $subcategorias = Categoria::obtenerSubcategorias($conn, $madre->getIdCategoria());
                // Si la categoría madre tiene una ruta definida, úsala como enlace
                $madreHref = $base . "contenidos.php?id=" . $madre->getIdCategoria();
            ?>
                <li class="dropdown">
                    <a href="<?= $madreHref ?>">
                        <?php if (!empty($madre->getIcono())): ?><i class="fa-solid <?= $madre->getIcono() ?>"></i><?php endif; ?>
                        <?= $madre->getTitulo() ?> <?php if (count($subcategorias) > 0) echo '▾'; ?>
                    </a>
                    
                    <?php if (count($subcategorias) > 0): ?>
                        <ul>
                            <?php foreach ($subcategorias as $hija): 
                                // Para cada subcategoría
                                $hijaHref = $base . "contenidos.php?id=" . $hija->getIdCategoria();
                            ?>
                                <li>
                                    <a href="<?= $hijaHref ?>">
                                        <?php if (!empty($hija->getIcono())): ?><i class="fa-solid <?= $hija->getIcono() ?>"></i><?php endif; ?>
                                        <?= $hija->getTitulo() ?>
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
                echo '<a class="logout-button" href="' . $base . 'logout.php">Cerrar sesión</a>';// Mostrar un enlace para cerrar sesión que redirecciona a logout.php
            } else {
                echo '<a class="loginbutton" href="' . $base . 'signin.php"><i class="fa-solid fa-user"></i>Login</a>';
            }
        ?>
    </section> <!-- Cierre del section sin nombre -->
</header> <!-- Cierre del header -->