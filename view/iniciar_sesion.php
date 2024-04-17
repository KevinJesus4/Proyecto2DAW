<?php
require_once '../controller/login.php';

$login = new login();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loginBtn'])) {
    $login->procesarLogin();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../diseño/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../diseño/css/login.css">
    <script src="../js/comprobaciones.js"></script>
    <title>Login</title>
</head>
<body>
    <div id="container">
        <div id="login_cont" class="login-form-container">
            <h2>Login</h2>

            <form action="" method="POST" class="login-form" onsubmit="return validaFormulario()">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario</label>
                    <input id="usuario" name="usuario" type="text" class="form-control">
                </div>
                <div>
                    <label for="clave" class="form-label">Contraseña</label>
                    <input id="clave" name="clave" type="password" class="form-control">
                </div>
                <button type="submit" name="loginBtn" id="loginBtn" class="btn btn-primary btn-lg btn-block">Logiiiin</button>
               
            </form>
        </div>
    </div>
</body>
</html>