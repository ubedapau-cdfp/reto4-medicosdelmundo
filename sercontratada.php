<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contratada</title>
<link rel="stylesheet" href="estilos.css">
</head>
<body>
<?php include 'barrasNavegacion\header.php'; ?>
<p>
<section class="contenidos">
    <p><b>Ser Contratada</b></p>
    <p>Escoge, del siguiente selector, el apartado que quieres leer:</p>
    <form action="" method='POST'>
        <select class="opciones" name="opciones">
            <option value="Edad">Edad</option>
            <option value="Nacionalidad">Nacionalidad</option>
            <option value="Relaciones Excluidas y Especiales">Relaciones Excluidas y Especiales</option>
        </select>
        <button type="Submit" value="Submit">Mostrar apartado</button>
    </form>
</section>

<section class="subapartados">
<?php 
// serContratada.php

    if (isset($_POST['opciones'])){
        // traemos la conexión con la bd
        include 'conexion.php'; 
        // Cargamos las clases 
        include 'clases/Categoria.php';
        include 'clases/Bloque.php';

        $categoria=$_POST['opciones'];

       try {
        $sql = "SELECT b.* FROM BLOQUE b 
                INNER JOIN CATEGORIA c ON b.id_categoria = c.id_categoria 
                WHERE c.titulo = :nombre 
                ORDER BY b.orden";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre', $categoria);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            foreach($result as $row) {
                // Se crea el objeto
                $nuevoBloque = new Bloque(
                    $row['id_bloque'],
                    $row['id_categoria'],
                    $row['titulo'],
                    $row['subtitulo'],
                    $row['contenido'],
                    $row['orden']
                );

                //la lógica de impresión está DENTRO de la clase.
                $nuevoBloque->mostrarDatos();
            }
          } else {
              // Corregido: usamos la variable $categoria que es la que viene del POST
              echo "No se encontraron resultados para: " . htmlspecialchars($categoria);
          }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
?>
</section>


<?php include 'barrasNavegacion\footer.php'; ?>


</body>
</html>