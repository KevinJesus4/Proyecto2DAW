<?php
include '../connect/conexionBD.php';
require_once '../connect/token.php'; 

session_start();
$token = new Token();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: iniciar_sesion.php");
    exit();
}
if ($token->verificarToken($_SESSION['usuario_id'])) {
} else {
    header("Location: ../view/iniciar_sesion.php");
}

if (isset($_GET['id'])) {
    $carrito_id = $_GET['id'];
    $conn = connection::dbConnection();
    $sql = "SELECT clienteID FROM Carrito WHERE id = $carrito_id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $cliente_id = $row["clienteID"];
        $sql_cliente = "SELECT * FROM Cliente WHERE id = $cliente_id";
        $result_cliente = mysqli_query($conn, $sql_cliente);

        if (mysqli_num_rows($result_cliente) > 0) {
            $row_cliente = mysqli_fetch_assoc($result_cliente);
            mysqli_close($conn);
        } else {
            echo "Cliente no encontrado";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Cliente</title>
    <link rel="stylesheet" href="/Proyecto/diseño/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../diseño/css/formulario.css">
</head>
<body>
    <div class="container custom-container">
        <h2>Detalles del Cliente</h2>
        <div class="mb-3">
            <label for="id" class="form-label">Cliente ID</label>
            <input type="text" class="form-control" id="id" value="<?php echo $row_cliente['id']; ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" value="<?php echo $row_cliente['nombreCli']; ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" class="form-control" id="apellido" value="<?php echo $row_cliente['apellido']; ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" value="<?php echo $row_cliente['emailCli']; ?>" disabled>
        </div>
        <a href="/Proyecto/view/menu.php" class="btn btn-success custom-btn">Ir al Menú</a>
    </div>

    <noscript id="noscript-message">
        <h1>Error: JavaScript está deshabilitado</h1>
        <p>El JavaScript ha sido deshabilitado. Por favor, vuelva a habilitarlo para utilizar la página de manera correcta. Gracias.</p>
    </noscript>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/funciones.js"></script>
</body>
</html>


