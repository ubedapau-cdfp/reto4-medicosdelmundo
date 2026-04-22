<?php
session_start();
session_destroy();
header('Location: /reto4-medicosdelmundo/home/home.php');
exit();
?>
