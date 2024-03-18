<?php
require_once 'conexionBD.php';

class Token {
    private $conexion;

    public function __construct() {
        
        $this->conexion = connection::dbConnection();
    }

    private function generateToken() {

        return bin2hex(random_bytes(32)); 
    }

    public function insertarToken($id_usuario) {
        
        $session_duration = 5 * 24 * 60 * 60; // 5 días de duracion
    
        $token = $this->generateToken();
        $token_expiracion = date('Y-m-d H:i:s', time() + $session_duration);
    
        $query = "INSERT INTO token (token, fecha_expiracion, id_usuario) VALUES (?, ?, ?)";
        $statement = $this->conexion->prepare($query);
        $statement->bind_param("sss", $token, $token_expiracion, $id_usuario);
        $statement->execute();
    }
    
    public function verificarToken($token, $id_usuario) {

        $current_time = date('Y-m-d H:i:s');
        $query = "SELECT * FROM token WHERE token = ? AND id_usuario = ? AND fecha_expiracion > ?";
        
        $statement = $this->conexion->prepare($query);
        $statement->bind_param("sis", $token, $id_usuario, $current_time);
        $statement->execute();
        
        $result = $statement->get_result();
        return $result->num_rows > 0;
    }
    

    public function eliminarToken($token) {

        $query = "DELETE FROM tokens WHERE token = ?";
        
        $statement = $this->conexion->prepare($query);
        $statement->bind_param("s", $token);
        $statement->execute();
    }
}
?>
