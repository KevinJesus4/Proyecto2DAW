<?php
session_start();
$_SESSION = array();
session_destroy();

header("Location: ../view/iniciar_sesion.php");
exit();
?>