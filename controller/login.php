<?php
require_once '../connect/conexionBD.php';
require_once '../connect/token.php';

class login { 

    public function procesarLogin() {
        
        $conn = connection::dbConnection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loginBtn'])) {

            $usuario = $_POST['usuario'];
            $clave = $_POST['clave'];
            $query = "SELECT id, email, clave FROM usuario WHERE email = ?";

            $statement = $conn->prepare($query);
            $statement->bind_param("s", $usuario);
            $statement->execute();
            $result = $statement->get_result();
        
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user_id = $row['id'];
                $stored_password = $row['clave'];
                
                if ($clave === $stored_password) {
                    $token = new Token();
                    $token->insertarToken($user_id);

                    session_start();
                    $_SESSION['usuario_id'] = $user_id;
    
                    header("Location: ../view/menu.php");
                    exit();
                }
            }
        }
        header("Location: ../view/iniciar_sesion.php");
        exit();
    }
    
}
?>
