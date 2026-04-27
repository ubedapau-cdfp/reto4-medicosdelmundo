<?php // Inicio del apartado PHP - abre el bloque PHP para ejecutar código de servidor
    // Incluimos la conexión POO y obtenemos las categorías madre
    include_once __DIR__ . "/../conexion.php"; // incluye el archivo conexion.php desde la carpeta padre
    if (session_status() === PHP_SESSION_NONE) session_start(); // inicia la sesión si no está iniciada
    $base = '/reto4-medicosdelmundo/'; // ruta base usada para construir enlaces relativos

    $database = new Database(); // crear instancia de Database para consultas
    $categorias_madre = $database->obtenerCategoriasMadre(); // recuperar categorías madre desde la BD
?> <!-- Cierre del apartado PHP --> <!-- cierra el bloque PHP y vuelve a HTML -->

<head> <!-- apertura de la sección head (metadatos y enlaces a CSS/JS) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> <!-- carga Font Awesome desde CDN -->
</head> <!-- cierre de head -->

<header> <!-- Inicio del header (cabecera y navegación) -->
    <a href="<?= $base ?>home/home.php" class="logo"> <!-- enlace al home usando la variable $base -->
        <img src="<?= $base ?>Imagenes/Logoreal.png" alt="Logo"> <!-- imagen del logo con ruta relativa -->
    </a> <!-- cierre del enlace del logo -->

    <nav> <!-- inicio de la navegación principal -->
        <ul> <!-- lista de items de navegación -->
            <?php  // abre bloque PHP para recorrer categorías dinámicas
            // 3. Recorremos las categorías madre
            foreach ($categorias_madre as $madre):  // bucle: por cada categoría madre
                // Obtener subcategorías usando la clase Database
                $subcategorias = $database->obtenerSubcategorias((int)$madre['id_categoria']); // obtiene subcategorías de la madre actual
                // Si la categoría madre tiene una ruta definida, úsala como enlace
                $madreHref = (isset($madre['ruta']) && !empty($madre['ruta'])) ? $base . $madre['ruta'] : '#'; // si hay ruta, construir href; si no, '#'
            ?>
                <li class="dropdown"> <!-- item de lista que puede contener submenú -->
                    <a href="<?= $madreHref ?>"> <!-- enlace de la categoría madre -->
                        <?php if (!empty($madre['icono'])): // si la categoría tiene un icono definido en BD ?>
                            <i class="<?= $madre['icono'] ?>"></i> <!-- mostrar el icono; la clase viene de la BD (ej. 'fa-solid fa-...') -->
                        <?php endif; // fin comprobación icono ?>
                        <?= $madre['titulo'] ?> <?php if (count($subcategorias) > 0) echo '▾'; ?> <!-- mostrar título y una flecha si tiene subcategorías -->
                    </a> <!-- cierre del enlace madre -->
                    
                    <?php if (count($subcategorias) > 0): // si existen subcategorías mostrar sublista ?>
                        <ul> <!-- lista de subcategorías -->
                            <?php foreach ($subcategorias as $hija):  // bucle por cada subcategoría
                                // Para cada subcategoría, si tiene 'ruta' usarla, si no usar contenidos.php?id=
                                $hijaHref = (isset($hija['ruta']) && !empty($hija['ruta'])) ? $base . $hija['ruta'] : $base . "contenidos.php?id=" . $hija['id_categoria']; // construir href para la hija
                            ?>
                                <li> <!-- item de sublista -->
                                    <a href="<?= $hijaHref ?>"> <!-- enlace de la subcategoría -->
                                        <?php if (!empty($hija['icono'])): // si la subcategoría tiene icono ?>
                                            <i class="<?= $hija['icono'] ?>"></i> <!-- mostrar icono de la subcategoría -->
                                        <?php endif; // fin comprobación icono hija ?>
                                        <?= $hija['titulo'] ?> <!-- mostrar título de la subcategoría -->
                                    </a> <!-- cierre enlace subcategoría -->
                                </li> <!-- fin item sublista -->
                            <?php endforeach; // fin foreach subcategorias ?>
                        </ul> <!-- cierre lista subcategorías -->
                    <?php endif; // fin if subcategorias ?>
                </li> <!-- cierre item madre -->
            <?php endforeach; // fin foreach categorias madre ?>
        </ul> <!-- cierre lista principal -->
    </nav> <!-- cierre nav -->

    <section class="enlaces-derecha"> <!-- Inicio del section enlaces-derecha (enlaces auxiliares) -->
        <a href="<?= $base ?>otros/sobrenosotras.php"><i class="fa-solid fa-users"></i>Sobre nosotras</a> <!-- enlace estático: Sobre nosotras con icono FA6 -->
        <a href="<?= $base ?>otros/preguntasfrecuentes.php"><i class="fa-solid fa-circle-question"></i>FAQs</a> <!-- enlace estático: FAQs con icono FA6 -->
    </section> <!-- Cierre del section enlaces-derecha -->

    <section class="admin-session"> <!-- Inicio del section de sesión/usuario -->
        <?php
            $page = basename($_SERVER['PHP_SELF']); // obtiene el nombre del archivo PHP actual (p. ej. 'home.php')

            if (isset($_SESSION['usuario_nombre'])) { // si existe la variable de sesión 'usuario_nombre'
                $nombre = $_SESSION['usuario_nombre']; // obtener el nombre del usuario desde la sesión
                echo "<span class='admin-name'>Hola, " . $nombre . "</span>"; // imprimir saludo con el nombre
                echo '<a class="logout-button" href="' . $base . 'logout.php">Cerrar sesión</a>'; // imprimir enlace para cerrar sesión
            } else {
                echo '<a class="loginbutton" href="' . $base . 'signin.php"><i class="fa-solid fa-user"></i>Login</a>'; // enlace login si no hay sesión
            }
        ?>
    </section> <!-- Cierre del section sin nombre -->
</header> <!-- Cierre del header -->