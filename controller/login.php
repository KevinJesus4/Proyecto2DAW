<?php
require_once '../connect/conexionBD.php';
require_once '../connect/token.php';

class login { 

    public function procesarLogin() {
        
        $conn = connection::dbConnection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loginBtn'])) {

            $usuario = $_POST['usuario'];
            $clave = $_POST['clave'];
        
            $query = "SELECT id_usuario, email FROM usuario WHERE nombre = ? AND clave = ?";

            $statement = $conn->prepare($query);
            $statement->bind_param("ss", $usuario, $clave);
            $statement->execute();
            $result = $statement->get_result();
        
            if ($result->num_rows > 0) {

                $row = $result->fetch_assoc();
                $user_id = $row['id_usuario'];    
                $token = new Token();
                $token->insertarToken($user_id); 
                header("Location: ../view/menu.php");
                exit();
            }
        }
        header("Location: ../view/iniciar_sesion.php");
        exit();
    }

    public function cerrarSesion() {
        
        session_start();
        session_unset();
        session_destroy();
        
        header("Location: ../view/iniciar_sesion.php");
        exit();
    }
}
?>