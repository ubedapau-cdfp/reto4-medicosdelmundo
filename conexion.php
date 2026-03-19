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
    //$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully";s
    } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    }
?>