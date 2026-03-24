<?php
 session_start();
 require_once 'conexion.php';
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
     $user = $_POST['usuaria'];
     $pass = $_POST['contrasena'];
     try {
        $sql = "SELECT id_usuario, password_hash FROM USUARIOS WHERE nombre = :nombre";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre', $user);
        $stmt->execute();
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($pass, $usuario['password_hash'])) {
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            header('Location: /reto4-medicosdelmundo/home/home.php');
            exit();
            } else {
                echo "<section class='loginFail'>Usuario o contraseña incorrectos.</section>";
                }
                } catch (PDOException $e) {
                    echo "<section class='loginFail'>Error: " . $e->getMessage()."</section>";
                    }
                    }
?>