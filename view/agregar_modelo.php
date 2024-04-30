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
    <title>Agregar Nuevo Modelo</title>
    <link rel="stylesheet" href="/Proyecto/diseño/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../diseño/css/formulario.css">
</head>
<body>
    <div class="container custom-container">
        <h1>Agregar Nuevo Modelo</h1>
        <form id="formAgregarModelo" onsubmit="return enviarFormularioAgregarModelo()">
            <div class="mb-3">
                <label for="nombre_modelo" class="form-label">Nombre del Modelo:</label>
                <input type="text" class="form-control" id="nombre_modelo" name="nombre_modelo">
            </div>
            <button type="submit" class="btn btn-primary custom-btn">Agregar Modelo</button>
            <button id="btnMenu" class="btn btn-success custom-btn">Ir al Menú</button>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../js/comprobaciones.js"></script>
    <script src="../js/funciones.js"></script>
</body>
</html>

