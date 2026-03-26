<?php 
    // Incluimos la conexión y las clases (Estilo W3Schools)
    include 'conexion.php'; 
    include 'clases/Categoria.php';
    include 'clases/Bloque.php';

    // Paso 1: Obtener las categorías ANTES de mostrar el HTML del selector
    $listaCategorias = Categoria::obtenerTodas($conn);
?>

<!DOCTYPE html> <!--  -->
<html lang="es"> <!--  -->
<head> <!--  -->
    <meta charset="UTF-8"> <!--  -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--  -->
    <title>Contratada</title> <!--  -->
    <link rel="stylesheet" href="estilos.css"> <!--  -->
</head> <!--  -->
<body> <!--  -->

<?php include 'barrasNavegacion\header.php'; ?> <!--  -->

<section class="contenidos"> <!-- Inicio del section contenidos -->
    <p><b>Ser Contratada</b></p> <!-- Párrafo de texto y en negrita, de título -->
    <p>Escoge un apartado:</p> <!-- Párrafo de texto -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"> <!-- Inicio del form de método POST  -->
        <select class="opciones" name="opciones"> <!--  -->
            <?php // Inicio del apartado PHP
            foreach ($listaCategorias as $cat){ // Foreach que recorre el array $listaCategorias mediante el valor $cat
                echo '<option value="' . $cat->getTitulo() . '">' . $cat->getTitulo() . '</option>'; // Dentro del option, recoge los títulos mediante un getTitulo mediante el valor $cat para mostrarlo como opcion
            }
            ?> <!-- Cierre del apartado PHP -->
        </select> <!-- -->
        <button type="submit">Mostrar apartado</button> <!-- -->
    </form> <!-- -->
</section> <!-- -->

<section class="subapartados"> <!-- Inicio del section subapartados -->
<?php // Inicio del apartado PHP
    // Paso 2: Procesar el formulario cuando se envía (POST)
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['opciones'])) { // Si $_SERVER['REQUEST_METHOD'] es POST y existe el $_POST['opciones']
        $nombreCat = $_POST['opciones']; // $_POST['opciones'] se guarda en el valor $nombreCat

        $listaBloques = Bloque::obtenerPorCategoria($conn, $nombreCat); // Llamada al método estático que creamos en $listaBloques

        if (!empty($listaBloques)) { // si $listaBloques no está vacío
            foreach($listaBloques as $bloque) { // Foreach que recorre el array $listaBloques mediante el valor $bloque
                $bloque->mostrarDatos(); // Ejecutamos la función mostrarDatos mediante el valor $bloque
            }
        } else { // En caso contrario, es decir, estar vacío
            echo "No se encontraron resultados."; // Enseñar mensaje "no hay resultados"
        }
    }
?> <!-- Cierre del apartado PHP -->
</section> <!-- Cierre del section subapartados -->
<?php include 'barrasNavegacion\footer.php'; ?>
</body> <!-- Cierre del body -->
</html> <!-- Cierre del HTML -->