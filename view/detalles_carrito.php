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

if (isset($_GET['carrito_id'])) {
    $carritoID = $_GET['carrito_id'];
    $carrito = Carrito::obtenerDetallesCarrito($carritoID);

} else {
    header("Location: alguna_pagina.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Carrito</title>
    <link rel="stylesheet" href="/ruta/a/tu/estilo.css">
</head>
<body>
    <h1>Detalles del Carrito</h1>
    <form>
        <label for="idCarrito">ID del Carrito:</label>
        <input type="text" id="idCarrito" name="idCarrito" value="<?php echo $carrito['id']; ?>" readonly><br>

        <label for="cliente">Cliente:</label>
        <input type="text" id="cliente" name="cliente" value="<?php echo $carrito['cliente']; ?>" readonly><br>

        <label for="marca">Marca:</label>
        <input type="text" id="marca" name="marca" value="<?php echo $carrito['nombre_marca']; ?>" readonly><br>

        <label for="modelo">Modelo:</label>
        <input type="text" id="modelo" name="modelo" value="<?php echo $carrito['nombre_modelo']; ?>" readonly><br>

        <label for="cantidad">Cantidad:</label>
        <input type="text" id="cantidad" name="cantidad" value="<?php echo $carrito['cantidad']; ?>" readonly><br>

        <label for="precioUnidad">Precio Unidad:</label>
        <input type="text" id="precioUnidad" name="precioUnidad" value="<?php echo $carrito['precioUnidad']; ?>" readonly><br>

        <a href="/Proyecto/view/menu.php">Volver a la lista de carritos</a>
    </form>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../js/funciones.js"></script>
</body>
</html>
<?php

