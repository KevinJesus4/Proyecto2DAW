<?php
session_start();

require_once '../connect/token.php'; 

$token = new Token();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: iniciar_sesion.php");
    exit();
}
if ($token->verificarToken($_SESSION['usuario_id'])) { 
} else {
    header("Location: ../view/iniciar_sesion.php");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario</title>
    <link rel="stylesheet" href="/Proyecto/diseño/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../diseño/css/formulario.css">
</head>
<body>
    <div class="container custom-container">
        <h2>Registrar Nuevo Cliente</h2>
        <form id="formularioCliente" method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombreCli">
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="apellido">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="emailCli">
            </div>
            <button type="submit" id="btnEnviar" class="btn btn-primary custom-btn">Registrar Cliente</button>
            <button id="btnMenu" class="btn btn-success custom-btn">Ir al Menú</button>
        </form>
    </div>

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/comprobaciones.js"></script>
    <script src="../js/funciones.js"></script>
</body>
</html>
