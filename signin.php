<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='estilos.css'>
    <title>Login</title>
</head>
<body>
    <section class="loginMenu">
        <section class="ladoIzqdo">
            <p></p>
        </section>
        <section class="ladoDcho">
            <section class="menuform">
                <h1>Inicio de Sesión</h1>
                &nbsp;
                <form action='' method='POST'>
                    <label for='usuaria'>Nombre:</label>
                    <input type='text' name='usuaria' id='usuaria' size='50' placeholder=' Usuaria01' required>
                    <p></p>
                    <label for='contrasena'>Contraseña:</label>
                    <input type='password' name='contrasena' size='46' id='contrasena' placeholder=' •••••••' required>
                    <p></p>
                    <button type='submit'>Iniciar Sesión</button><button type='submit'><a href='home.php'>Volver a Inicio</a></button>
                    <p></p>
                    <?php
                        session_start();
                        require_once 'conexion.php';

                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $user = $_POST['usuaria'];
                            $pass = $_POST['contrasena'];
                            $hash = password_hash($pass, PASSWORD_DEFAULT);

                            try {
                                $sql = "SELECT id_usuario, password_hash FROM USUARIOS WHERE nombre = :nombre";
                                $stmt = $conn->prepare($sql);
                                $stmt->bindParam(':nombre', $user);
                                $stmt->execute();
                                
                                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                                // Verificamos si el usuario existe y si la contraseña coincide con el hash
                                if ($usuario && password_verify($pass, $usuario['password_hash'])) {
                                    $_SESSION['usuario_id'] = $usuario['id_usuario'];
                                    header('Location: home.php');
                                    exit();
                                } else {
                                    echo "<section class='loginFail'>Usuario o contraseña incorrectos.</section>";
                                }
                            } catch (PDOException $e) {
                                echo "<section class='loginFail'>Error: " . $e->getMessage()."</section>";
                            }
                        }
                        ?>
                </form>
            </section>
        </section>
    </section>
</body>
</html> 