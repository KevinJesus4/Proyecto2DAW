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
    <title>Compra de Zapatillas</title>
    <link rel="stylesheet" href="/Proyecto/diseño/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Proyecto/diseño/css/menu.css">

</head>
<body>
    <div id="cabecera" class="cabecera">
        <header class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="#" style="color: white; font-weight: bold;">KevStore</a>
                <form id="logoutForm" class="form-inline my-2 my-lg-0" method="post" action="../controller/cerrar_sesion.php">
                    <button class="btn btn-danger my-2 my-sm-0" type="submit" name="logoutBtn">Cerrar sesión</button>
                </form>
            </div>
        </header>
    </div>


    <div id="cuerpo">
        <nav class="menu">
            <ul>
                <li><a href="#" onclick="mostrarContenido('inicio')">Inicio</a></li>
                <li><a href="#" onclick="mostrarContenido('marcas')">Marcas</a></li>
                <li><a href="#" onclick="mostrarContenido('modelos')">Modelos</a></li>
                <li><a href="#" onclick="mostrarContenido('productos')">Productos</a></li>
                <li><a href="#" onclick="mostrarContenido('comprar')">Comprar</a></li>
                <li><a href="#" onclick="mostrarContenido('clientes')">Clientes</a></li>
                <li><a href="#" onclick="mostrarContenido('carrito')">Carrito</a></li>
            </ul>
        </nav><br>

        <div id="infoTienda" class="container">
        <h2>Información de la Tienda</h2>
        <p>¡Bienvenido a KevStore! Somos tu mejor opción para comprar zapatillas de calidad.</p>
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

        <div id="comprar" class="container" style="display: none;">
            <h2>Comprar</h2>
        </div>

        <div id="clientes" class="container" style="display: none;">
            <h2>Clientes</h2>
        </div>

        <div id="carrito" class="container" style="display: none;">
            <h2>Carrito</h2>
        </div>
    </div>

    <footer class="pie" id="pie">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h4>Dirección:</h4>
                <p>Calle de la Ronaldo, 7, Madrid, España</p>
            </div>
            <div class="col-md-6">
                <h4>Contacto:</h4>
                <p>Teléfono: +123456789</p>
                <p>Email: info@kevstore.com</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4>Síguenos en:</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p>&copy; 2024 KevStore. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>
</footer>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../js/funciones.js"></script>
</body>

