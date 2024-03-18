<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Precio</title>
    <link rel="stylesheet" href="/Proyecto/diseño/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../diseño/css/formulario.css">
</head>
<body>
    <div class="container custom-container">
        <h2>Actualizar Precio del Producto</h2>
        <form id="formularioPrecio" method="PUT">
            <div class="mb-3">
                <label for="id_producto" class="form-label">ID Producto</label>
                <input type="text" class="form-control" id="id_producto" name="id_producto">
            </div>
            <div class="mb-3">
                <label for="nuevo_precio" class="form-label">Nuevo Precio</label>
                <input type="text" class="form-control" id="nuevo_precio" name="nuevo_precio">
            </div>
            <button type="submit" class="btn btn-primary custom-btn">Actualizar Precio</button>
            <button id="btnMenu" class="btn btn-success custom-btn">Ir al Menú</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/funciones.js"></script>
</body>
</html>
