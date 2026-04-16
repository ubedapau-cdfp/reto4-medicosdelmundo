<?php
session_start(); // Inicia la sesión
require_once 'conexion.php'; // Incluye la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Solo procesa si el formulario es de tipo POST
    $user = $_POST['usuaria']; // Recoge el nombre de usuario 
    $pass = $_POST['contrasena']; // Recoge la contraseña 

    try {
        $sql = "SELECT id_usuario, password_hash, id_rol FROM USUARIOS WHERE nombre = :nombre"; // Consulta el usuario por nombre
        $stmt = $conn->prepare($sql); // Prepara la consulta
        $stmt->bindParam(':nombre', $user); // Asignación de parámetros
        $stmt->execute(); // Ejecuta la consulta

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC); // Obtiene el resultado

        if ($usuario && password_verify($pass, $usuario['password_hash'])) { // Verifica la contraseña
            $_SESSION['usuario_id'] = $usuario['id_usuario']; // Almacena el id del usuario en sesión
            $_SESSION['usuario_nombre'] = $user; // Almacena el nombre de usuario en sesión
            $_SESSION['usuario_rol'] = $usuario['id_rol']; // Almacena el rol en sesión

            if ($usuario['id_rol'] === '3') { // Si es admin
                header('Location: /reto4-medicosdelmundo/Vistadmin/Menu.php'); // Redirige al menú de admin
            } else {
                header('Location: /reto4-medicosdelmundo/home/home.php'); // Redirige al home para demás roles
            }
            exit(); // Finaliza la ejecución después de la redirección
        } else {
            echo "<section class='loginFail'>Usuario o contraseña incorrectos.</section>"; // Mensaje de error de autenticación
        }
    } catch (PDOException $e) { // Captura errores de base de datos
        echo "<section class='loginFail'>Error: " . $e->getMessage() . "</section>"; // Muestra el mensaje de error
    }
}
?>