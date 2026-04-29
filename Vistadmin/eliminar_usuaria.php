<?php
session_start();
// Acceso para administradoras (id_rol === 3)
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['id_rol']) || intval($_SESSION['id_rol']) !== 3) {
    header('Location: /reto4-medicosdelmundo/signin.php');
    exit();
}

require_once __DIR__ . '/../clases/Usuario.php';

if (isset($_GET['id']) && intval($_GET['id']) > 0) {
    $id = intval($_GET['id']);
    $usuaria = Usuario::RecuperarByID($id);
    if ($usuaria) {
        $usuaria->eliminar();
    }
}

header('Location: panelusuarias.php');
exit();
?>