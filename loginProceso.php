<?php // Inicio del apartado PHP
 session_start(); // Inicio de sesión
 require_once 'conexion.php'; // Incluimos archivo conexion.php para conexión a BD
 $database = new Database(); // Creamos instancia de clase Database
 $conn = $database->conectar(); // Conectamos a la base de datos y guardamos la conexión en $conn
 if ($_SERVER["REQUEST_METHOD"] == "POST") { // Verificamos que el método de la solicitud sea POST
     $user = $_POST['usuaria']; // Guardamos el valor del campo 'usuaria' del formulario en la variable $user
     $pass = $_POST['contrasena']; // Guardamos el valor del campo 'contrasena' del formulario en la variable $pass
     try { // Intentamos ejecutar el bloque de código para autenticación
        $sql = "SELECT id_usuario, password_hash, id_rol, nombre FROM USUARIOS WHERE nombre = :usuario"; // SQL
        $stmt = $conn->prepare($sql); // Preparación de la consulta
        $stmt->bindParam(':usuario', $user); // Vinculamos el parámetro ':usuario' con la variable $user
        $stmt->execute(); // Ejecutamos la consulta
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC); // Obtenemos el resultado de la consulta como un array
        if ($usuario && password_verify($pass, $usuario['password_hash'])) { // Verificamos que el usuario exista y que la contraseña sea correcta
            $_SESSION['usuario_id'] = $usuario['id_usuario']; // El ID usuario se guarda en la sesión
            $_SESSION['id_rol'] = intval($usuario['id_rol']); // El ID rol se guarda en la sesión
            $_SESSION['usuario_nombre'] = $usuario['nombre']; // El nombre de usuario se guarda en la sesión
            $_SESSION['usuario_login'] = $user; // El nombre de usuario se guarda en la sesión
            
            $rol = intval($usuario['id_rol']); // Guardamos el ID de rol en la variable $rol para redirección
            if ($rol === 3) { // Si el rol es 3, Administradora
                header('Location: /reto4-medicosdelmundo/Vistadmin/Menu.php'); // Redirección a menú de administradora
            } elseif ($rol === 2) { // Si el rol es 2, Orientadora
                header('Location: /reto4-medicosdelmundo/VistaOrientadora/Menu.php'); // Redirección a menú de orientadora
            } else { // Si el rol no es ni 2 ni 3
                header('Location: /reto4-medicosdelmundo/home/home.php'); // Redirección a página de inicio
            }
            exit();
            } else { // Si el usuario no existe o la contraseña es incorrecta
                echo "<section class='loginFail'>Usuario o contraseña incorrectos.</section>"; // Mostramos mensaje de error
                }
                } catch (PDOException $e) {
                    echo "<section class='loginFail'>Error: " . $e->getMessage()."</section>";
                    }
                    }
?>