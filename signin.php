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
                    <button type='submit'>Iniciar Sesión</button>
                    <button type='submit'><a href='/reto4-medicosdelmundo/home/home.php'>Volver a Inicio</a></button>
                    <p></p>
                    <?php require_once 'loginProceso.php' ?>
                </form>
            </section>
        </section>
    </section>
</body>
</html>