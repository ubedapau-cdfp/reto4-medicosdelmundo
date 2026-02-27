<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ser Contratada</title>
<link rel="stylesheet" href="prueba.css">
</head>
<body>
<?php include 'barrasNavegacion\header.php'; ?>
<p>
<section class="contenidos">
    <p><b>Ser Contratada</b></p>
    <p>Escoge, del siguiente selector, el apartado que quieres leer:</p>
    <form action="" method='POST'>
        <select class="opciones" name="opciones">
            <option value="7">Edad</option>
            <option value="8">Nacionalidad</option>
            <option value="9">Relaciones Excluidas y Especiales</option>
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
        // Cargamos las clases necesarias desde tu carpeta 'clases'
        include 'clases/Categoria.php';
        include 'clases/Bloque.php';

        $categoria=$_POST['opciones'];

       try {
        // Preparamos la consulta SQL; smt: método para hacer inyección segura SQL
        $stmt = $conn->prepare("SELECT * FROM BLOQUE WHERE id_categoria = :id ORDER BY orden");
        $stmt->bindParam(':id', $categoria);
        $stmt->execute();

        // Obtenemos los resultados
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($result) > 0) {
          foreach($result as $row) {
            
            // Creamos el objeto de forma simple
            $bloqueCreado = new Bloque(
              $row['id_bloque'],
              $row['id_categoria'],
              $row['titulo'],
              $row['subtitulo'],
              $row['contenido'],
              $row['orden']
            );

            // Mostramos el contenido usando las propiedades del objeto
            echo "<h2>" . $bloqueCreado->titulo . "</h2>";
            if ($bloqueCreado->subtitulo) {
                echo "<h3>" . $bloqueCreado->subtitulo . "</h3>";
            }
            echo "<p>" . $bloqueCreado->contenido . "</p>";
            echo "<hr>";
          }
        } else {
          echo "No se encontró contenido para esta selección.";
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