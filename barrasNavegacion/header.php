<!--header.php (ORIGINAL)-->
<?php // Inicio del apartado PHP
    // conexion.php está en la raíz, así que subimos un nivel
    include_once __DIR__ . "/../conexion.php";

    // Categoria.php está dentro de la carpeta 'clases', que está un nivel arriba
    include_once __DIR__ . "/../clases/Categoria.php";
    if (session_status() === PHP_SESSION_NONE) session_start();
    $base = '/reto4-medicosdelmundo/'; // Valor $base equivale a la ruta absoluta para su uso en la página

    // 2. Inicializamos la conexión POO
    $database = new Database();
    $conn = $database->conectar();

    // 3. Llamamos al método estático de la clase Categoria
    $categorias_madre = Categoria::obtenerCategoriasMadre($conn);
?> <!-- Cierre del apartado PHP -->

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<header> <!-- Inicio del header -->
    <a href="<?= $base ?>home/home.php" class="logo"> <!-- Inicio del enlace, que redirecciona al home.php -->
        <img src="<?= $base ?>Imagenes/Logoreal.png" alt="Logo"> <!-- Imagen del logo de la ONG -->
    </a> <!-- Cierre del enlace -->

    <nav> <!-- Inicio del nav -->
        <ul> <!-- Inicio de la lista desordenada general -->
            <?php 
                // 3. Recorremos las categorías madre
                foreach ($categorias_madre as $madre): 
                // Obtenemos las subcategorías para esta madre específica
                $subcategorias = Categoria::obtenerSubcategorias($conn, $madre->getIdCategoria());
            ?>
            <li class="dropdown"> <!-- Inicio del ítem de lista class dropdown -->
                <a href="#"><i class="fa-solid fa-file-contract"></i>Contratos ▾</a> <!-- Enlace sin redirección que muestra los apartados, título del apartado -->
                <ul> <!-- Inicio de lista desordenada del class dropdown --> 
                    <li><a href="<?= $base ?>contratos/relacionlaboral.php"><i class="fa-solid fa-id-card"></i>Requisitos para la Relación Laboral</a></li> <!-- Ítem de lista con enlace que redirecciona a relacionlaboral.php -->
                    <li><a href="<?= $base ?>contratos/normativayderechos.php"><i class="fa-solid fa-scale-balanced"></i>Jerarquía normativa y derechos</a></li> <!-- Ítem de lista con enlace que redirecciona a normativayderechos.php -->
                </ul> <!-- Cierre de lista desordenada del class dropdown -->
            </li> <!-- Cierre de ítem de lista del class dropdown -->

            <li class="dropdown"> <!-- Inicio del ítem de lista class dropdown -->
                <a href="#"><i class="fa-solid fa-list-check"></i>Elementos y Condiciones del Contrato ▾</a> <!-- Enlace sin redirección que muestra los apartados, título del apartado -->
                <ul> <!-- Inicio de lista desordenada del class dropdown --> 
                    <li><a href="<?= $base ?>elementosYcondiciones/elementossustanciales.php"><i class="fa-solid fa-key"></i>Elementos Sustanciales</a></li> <!-- Ítem de lista con enlace que redirecciona a elementossustanciales.php -->
                    <li><a href="<?= $base ?>elementosYcondiciones/elementosimportantes.php"><i class="fa-solid fa-circle-exclamation"></i>Elementos Importantes pero No Imprescindibles</a></li> <!-- Ítem de lista con enlace que redirecciona a elementosimportantes.php -->
                </ul> <!-- Cierre de lista desordenada del class dropdown -->
            </li> <!-- Cierre de ítem de lista del class dropdown -->

            <li class="dropdown"> <!-- Inicio del ítem de lista class dropdown -->
                <a href="#"><i class="fa-solid fa-user-plus"></i>Proceso de Contratación y Requisitos ▾</a> <!-- Enlace sin redirección que muestra los apartados, título del apartado -->
                <ul> <!-- Inicio de lista desordenada del class dropdown --> 
                    <li><a href="<?= $base ?>procesoDeContratacionRequisitos/obligacionesempresa.php"><i class="fa-solid fa-building-shield"></i>Obligaciones de la Empresa</a></li> <!-- Ítem de lista con enlace que redirecciona a obligacionesempresa.php -->
                    <li><a href="<?= $base ?>procesoDeContratacionRequisitos/requisitostrabajadora.php"><i class="fa-solid fa-clipboard-user"></i>Requisitos de la Trabajadora</a></li> <!-- Ítem de lista con enlace que redirecciona a requisitostrabajadora.php -->
                    <li><a href="<?= $base ?>procesoDeContratacionRequisitos/nacionalidad.php"><i class="fa-solid fa-passport"></i>Nacionalidad</a></li> <!-- Ítem de lista con enlace que redirecciona a nacionalidad.php -->
                    <li><a href="<?= $base ?>procesoDeContratacionRequisitos/relacionesexcluidasyespeciales.php"><i class="fa-solid fa-hand-dots"></i>Relaciones Excluidas y Especiales</a></li> <!-- Ítem de lista con enlace que redirecciona a relacionesexcluidasyespeciales.php -->
                </ul> <!-- Cierre de lista desordenada del class dropdown -->
            </li> <!-- Cierre de ítem de lista del class dropdown -->

            <li class="dropdown"> <!-- Inicio del ítem de lista class dropdown -->
                <a href="#"><i class="fa-solid fa-handshake"></i>Relación Laboral ▾</a> <!-- Enlace sin redirección que muestra los apartados, título del apartado -->
                <ul> <!-- Inicio de lista desordenada del class dropdown --> 
                    <li><a href="<?= $base ?>RelacionLaboral/definicionyrequisitos.php"><i class="fa-solid fa-book-bookmark"></i>Definición y Requisitos</a></li> <!-- Ítem de lista con enlace que redirecciona a definicionyrequisitos.php -->
                    <li><a href="<?= $base ?>RelacionLaboral/principiosgeneralesderecholaboral.php"><i class="fa-solid fa-gavel"></i>Principios Generales del Derecho Laboral</a></li> <!-- Ítem de lista con enlace que redirecciona a principiosgeneralesderecholaboral.php -->
                </ul> <!-- Cierre de lista desordenada del class dropdown -->
            </li> <!-- Cierre de ítem de lista del class dropdown -->
            <?php endforeach; ?>
        </ul> <!-- Cierre de lista desordenada general -->
    </nav> <!-- Cierre del nav -->

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
    echo "<button class='logoutbutton'>"; // Botón para cerrar sesión
    echo "<a href='" . $base . "logout.php'>Cerrar Sesión</a>";// Mostrar un enlace para cerrar sesión que redirecciona a logout.php
    echo "</button>";
    
} else {
    echo "<button class='loginbutton'>";
    echo "<a href='" . $base . "signin.php'>"."<i class=\"fa-solid fa-user\"></i>"."Login</a>"; // Botón que redirecciona al signin.php
    echo "</button>";
}

?>
    </section> <!-- Cierre del section sin nombre -->
</header> <!-- Cierre del header -->