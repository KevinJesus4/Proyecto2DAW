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
    <title>Actualizar Precio de Producto</title>
    <link rel="stylesheet" href="/Proyecto/diseño/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../diseño/css/formulario.css">
</head>
<body>
    <div class="container custom-container">
        <h1>Actualizar Precio de Producto</h1>
        <form id="formActualizarPrecio">
            <div class="mb-3">
                <label for="productoID" class="form-label">ID del Producto:</label>
                <input type="number" class="form-control" id="productoID" name="productoID">
            </div>
            <div class="mb-3">
                <label for="nuevoPrecio" class="form-label">Nuevo Precio:</label>
                <input type="number" class="form-control" id="nuevo_precio" name="nuevoPrecio" step="0.01">
            </div>
            <button type="submit" class="btn btn-primary custom-btn">Actualizar Precio</button>
            <button id="btnMenu" class="btn btn-success custom-btn">Ir al Menú</button>
        </form>
    </div>
    <noscript id="noscript-message">
        <h1>Error: JavaScript está deshabilitado</h1>
        <p>El JavaScript ha sido deshabilitado. Por favor, vuelva a habilitarlo para utilizar la página de manera correcta. Gracias.</p>
    </noscript>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../js/comprobaciones.js"></script>
    <script src="../js/funciones.js"></script>
</body>
</html>
