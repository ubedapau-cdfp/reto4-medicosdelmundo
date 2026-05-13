<?php // Inicio del apartado PHP
    // Incluimos la conexión POO y obtenemos las categorías madre
    include_once __DIR__ . "/../conexion.php"; // Incluimos la conexión a la base de datos usando POO para poder establecer la conexión y realizar consultas
    include_once __DIR__ . "/../clases/Categoria.php"; // Incluimos la clase Categoria para poder usar sus métodos y obtener las categorías madre y subcategorías
    if (session_status() === PHP_SESSION_NONE) session_start();
    $base = '/reto4-medicosdelmundo/'; // Valor $base equivale a la ruta absoluta para su uso en la página

    $database = new Database(); // Creamos una instancia de la clase Database para establecer la conexión a la base de datos
    $conn = $database->conectar(); // Establecemos la conexión a la base de datos y obtenemos el objeto de conexión en la variable $conn
    $categorias_madre = Categoria::obtenerCategoriasMadre($conn); // Obtenemos las categorías madre usando obtenerCategoriasMadre de Categoria
?> <!-- Cierre del apartado PHP -->

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> <!-- Incluimos el CSS de Font Awesome -->
</head>

<header> <!-- Inicio del header -->
    <a href="<?= $base ?>index.php" class="logo"> <!-- Inicio del enlace, que redirecciona al index.php -->
        <img src="<?= $base ?>Imagenes/Logoreal.png" alt="Logo"> <!-- Imagen del logo de la ONG -->
    </a> <!-- Cierre del enlace -->

    <nav>
        <ul>
            <?php 
            foreach ($categorias_madre as $madre): // Para cada categoría madre obtenida
                $subcategorias = Categoria::obtenerSubcategorias($conn, $madre->getIdCategoria()); // Obtenemos las subcategorías de la categoría madre usando obtenerSubcategorias de Categoria
                $madreHref = $base . "contenidos.php?id=" . $madre->getIdCategoria(); // Construimos la URL para la categoría madre, redireccionando a contenidos.php con el id de la categoría como parámetro
            ?>
                <li class="dropdown"> 
                    <a href="<?= $madreHref ?>"> <!-- Enlace para la categoría madre, tomando su id de la categoría como parámetro -->
                        <?php if (!empty($madre->getIcono())): ?><i class="fa-solid <?= $madre->getIcono() ?>"></i><?php endif; ?> <!-- Si la categoría madre tiene un icono definido, lo mostramos usando Font Awesome -->
                        <?= $madre->getTitulo() ?> <?php if (count($subcategorias) > 0) echo '▾'; ?> <!-- Mostramos el título de la categoría madre y un símbolo de flecha hacia abajo si tiene subcategorías -->
                    </a>
                    
                    <?php if (count($subcategorias) > 0): ?> <!-- Si la categoría madre tiene subcategorías, mostramos un menú desplegable -->
                        <ul>
                            <?php foreach ($subcategorias as $hija): // Bucle para cada subcategoría de la categoría madre
                                $hijaHref = $base . "contenidos.php?id=" . $hija->getIdCategoria(); // Construimos la URL para la subcategoría, tomando el id de la categoría como parámetro
                            ?>
                                <li>
                                    <a href="<?= $hijaHref ?>"> <!-- Enlace para la subcategoría, tomando el id de la categoría como parámetro -->
                                        <?php if (!empty($hija->getIcono())): ?><i class="fa-solid <?= $hija->getIcono() ?>"></i><?php endif; ?> <!-- Si la subcategoría tiene un icono definido, lo mostramos usando Font Awesome -->
                                        <?= $hija->getTitulo() ?> <!-- Mostramos el título de la subcategoría y su icono si lo tiene definido -->
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
                echo '<a class="loginbutton" href="' . $base . 'signin.php"><i class="fa-solid fa-user"></i>Login</a>'; // Si el usuario no ha iniciado sesión, redireccion a login
            }
        ?>
    </section> <!-- Cierre del section sin nombre -->
</header> <!-- Cierre del header -->
