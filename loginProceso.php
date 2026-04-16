<?php
 session_start();
 require_once 'conexion.php';
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
     $user = $_POST['usuaria'];
     $pass = $_POST['contrasena'];
     try {
        $sql = "SELECT id_usuario, password_hash, id_rol, nombre FROM USUARIOS WHERE nombre = :nombre";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre', $user);
        $stmt->execute();
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($pass, $usuario['password_hash'])) {
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['id_rol'] = intval($usuario['id_rol']);
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_login'] = $user;
            
            $rol = intval($usuario['id_rol']);
            if ($rol === 3) {
                header('Location: /reto4-medicosdelmundo/Vistadmin/Menu.php');
            } elseif ($rol === 2) {
                header('Location: /reto4-medicosdelmundo/VistaOrientadora/Menu.php');
            } else {
                header('Location: /reto4-medicosdelmundo/home/home.php');
            }
            exit();
            } else {
                echo "<section class='loginFail'>Usuario o contraseña incorrectos.</section>";
                }
                } catch (PDOException $e) {
                    echo "<section class='loginFail'>Error: " . $e->getMessage()."</section>";
                    }
                    }
?>