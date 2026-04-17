<?php
// conexion.php
    //conectar la bd con los datos de postgres
    $host = "192.168.4.18"; //el nombre del servidor (servername)
    $port = "5432";
    $dbname = "medicosDelMundo"; // Asegúrate de que este es el nombre de tu BD
    $user = "postgres";
    $password = "P@ssw0rd"; // La contraseña de Postgres

    try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    // Mensaje de error por si acaso
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully";
    } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
    }

    // --- NUEVAS FUNCIONES PARA EL HEADER ---

// Función para obtener las Categorías Madre
    function obtenerCategoriasMadre($conexion) {
        $sql = "SELECT * FROM CATEGORIA WHERE id_madre IS NULL ORDER BY id_categoria ASC";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Función para obtener las Subcategorías de una Madre específica
    function obtenerSubcategorias($conexion, $id_madre) {
        $sql = "SELECT * FROM CATEGORIA WHERE id_madre = :id_madre ORDER BY id_categoria ASC";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id_madre', $id_madre, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
?>