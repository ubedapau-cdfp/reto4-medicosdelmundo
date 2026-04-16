<?php // Inicio del apartado PHP
 session_start(); // Inicio de la sesión
 require_once 'conexion.php'; // Recogiendo a conexion.php
 if ($_SERVER["REQUEST_METHOD"] == "POST") { // Si el método de petición del servidor es POST
     $user = $_POST['usuaria']; // Recoge el usuario en la variable $user mediante el $_POST['usuaria']
     $pass = $_POST['contrasena']; // Recoge la contraseña en la variable $pass mediante el $_POST['contrasena']
     try { // Intenta
        $sql = "SELECT id_usuario, password_hash FROM USUARIOS WHERE nombre = :nombre"; // 
        $stmt = $conn->prepare($sql); // 
        $stmt->bindParam(':nombre', $user); // 
        $stmt->execute(); // 
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC); // 

        if ($usuario && password_verify($pass, $usuario['password_hash'])) { // Si el usuario y la contraseña son iguales a las de la base de datos
            $_SESSION['usuario_id'] = $usuario['id_usuario']; // La sesión del usuario se guarda dentro de la variable $usuario
            header('Location: /reto4-medicosdelmundo/home/home.php'); // Redirecciona al home.php
            exit(); // Finaliza la función
            } else { // En caso contrario
                echo "<section class='loginFail'>Usuario o contraseña incorrectos.</section>"; // Ejecuta un mensaje de error de usuario o contraseña incorrectos
            }
                } catch (PDOException $e) { // Si existe alguna excepción, es decir, error
                    echo "<section class='loginFail'>Error: " . $e->getMessage()."</section>"; // Ejecuta el mensaje de error.
                }
        }
?> <!-- Fin del apartado PHP -->