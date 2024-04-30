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
    <title>Agregar Nuevo Producto</title>
    <link rel="stylesheet" href="/Proyecto/diseño/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../diseño/css/formulario.css">
</head>
<body>
    <div class="container custom-container">
        <h1>Agregar Nuevo Producto</h1>
        <form id="formAgregarProducto">
            <div class="mb-3">
                <label for="marcaID" class="form-label">ID de la Marca:</label><br>
                <select class="form-select" id="marcaID" name="marcaID"></select>
            </div>
            <div class="mb-3">
                <label for="modeloID" class="form-label">Modelo:</label><br>
                <select class="form-select" id="modeloID" name="modeloID"></select>
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Stock:</label>
                <input type="number" class="form-control" id="stock" name="stock">
            </div>
            <div class="mb-3">
                <label for="precioUnidad" class="form-label">Precio Unidad:</label>
                <input type="number" class="form-control" id="precioUnidad" name="precioUnidad" step="0.01">
            </div>
            <button type="submit" class="btn btn-primary custom-btn">Agregar Producto</button>
            <button id="btnMenu" class="btn btn-success custom-btn">Ir al Menú</button>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../js/comprobaciones.js"></script>
    <script src="../js/funciones.js"></script>
</body>
</html>

