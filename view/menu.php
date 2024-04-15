<?php
require_once '../controller/login.php';

$login = new login();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logoutBtn'])) {
    $login->cerrarSesion(); // Comprobamos si se ha enviado el formulario para cerrar sesión
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra de Zapatillas</title>
    <link rel="stylesheet" href="/Proyecto/diseño/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Proyecto/diseño/css/menu.css">

</head>
<body>
    <header id="encabezado" class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#" style="color: white; font-weight: bold;">KevStore</a>
            <form id="logoutForm" class="form-inline my-2 my-lg-0" method="post" action="">
                <button class="btn btn-danger my-2 my-sm-0" type="submit" name="logoutBtn">Cerrar sesiOOOón</button>
            </form>
        </div>
    </header>


    <nav class="menu">
        <ul>
            <li><a href="#" onclick="mostrarContenido('inicio')">Inicio</a></li>
            <li><a href="#" onclick="mostrarContenido('marcas')">Marcas</a></li>
            <li><a href="#" onclick="mostrarContenido('modelos')">Modelos</a></li>
            <li><a href="#" onclick="mostrarContenido('productos')">Productos</a></li>
            <li><a href="#" onclick="mostrarContenido('clientes')">Clientes</a></li>
            <li><a href="#" onclick="mostrarContenido('carrito')">Carrito</a></li>
        </ul>
    </nav><br>

    <div id="infoTienda" class="container">
    <h2>Información de la TiendAAAAa</h2>
    <p>¡Bienvenido a KevStore! Somos tu mejor opción para comprar zapatillas de calidad AAAAA.</p>
        <div id="imagen-container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="imagen-con-nombre">
                        <img src="/Proyecto/diseño/imagen/nike1.png" alt="Nike Zapatilla 1" class="img-fluid">
                        <p>Nike Air Monarch IV</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="imagen-con-nombre">
                        <img src="/Proyecto/diseño/imagen/nike2.jpg" alt="Nike Zapatilla 2" class="img-fluid">
                        <p>Nike Air Force 1 Max</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="imagen-con-nombre">
                        <img src="/Proyecto/diseño/imagen/nike3.png" alt="Nike Zapatilla 3" class="img-fluid">
                        <p>Nike Air Max</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="imagen-con-nombre">
                        <img src="/Proyecto/diseño/imagen/adidas1.jpg" alt="Adidas Zapatilla 1" class="img-fluid">
                        <p>Adidas Gazelle</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="imagen-con-nombre">
                        <img src="/Proyecto/diseño/imagen/adidas2.jpg" alt="Adidas Zapatilla 2" class="img-fluid">
                        <p>Adidas Forum</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="imagen-con-nombre">
                        <img src="/Proyecto/diseño/imagen/adidas3.png" alt="Adidas Zapatilla 3" class="img-fluid">
                        <p>Adidas Rivalry</p>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div id="marcas" class="container" style="display: none;">
        <h2>Marcas</h2>
    </div>

    <div id="modelos" class="container" style="display: none;">
        <h2>Modelos</h2>
    </div><br>

    <div id="productos" class="container" style="display: none;">
        <h2>Productos</h2>
    </div>

    <div id="clientes" class="container" style="display: none;">
        <h2>Clientes</h2>
    </div>


    <div id="carrito" class="container" style="display: none;">
        <h2>Carrito</h2>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../js/funciones.js"></script>
</body>

