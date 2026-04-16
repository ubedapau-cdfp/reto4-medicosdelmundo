<?php
session_start();
session_destroy();
header('Location: /reto4-medicosdelmundo/signin.php');
exit();
?>
