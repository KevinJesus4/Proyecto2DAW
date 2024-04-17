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

    public function insertarToken($usuarioID) {
        $session_duration = 1 * 60; 
    
        $token = $this->generateToken();
        $token_expiracion = date('Y-m-d H:i:s', time() + $session_duration);
    
        $query = "INSERT INTO token (token, fecha_expiracion, usuarioID) VALUES (?, ?, ?)";
        $statement = $this->conexion->prepare($query);
        $statement->bind_param("sss", $token, $token_expiracion, $usuarioID);
        $statement->execute();
    }
    
    public function verificarToken($usuarioID) {
        $current_time = date('Y-m-d H:i:s');
        $query = "SELECT * FROM token WHERE usuarioID = ? ORDER BY fecha_expiracion DESC LIMIT 1";
        
        $statement = $this->conexion->prepare($query);
        $statement->bind_param("i", $usuarioID);
        $statement->execute();

        
        
        $result = $statement->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $token = $row['token'];
            $expiracion = $row['fecha_expiracion'];
            
            if ($expiracion > $current_time) {
                // El token sigue siendo válido
                echo "Token obtenido correctamente.\n";
                echo "Token actual: $token\n";
                echo "Fecha de expiración: $expiracion\n";
                return true; // Esta línea termina la ejecución de la función
            } else {
                // El token ha expirado, eliminarlo de la base de datos
                // $this->eliminarToken($token);
                echo "El token ha expirado.";
                return false;
            }
            
        } else {
            // No se encontraron tokens para este usuario
            echo "No se encontraron tokens para este usuario.";
            return false;
        }
    }
    
    
    

    // public function eliminarToken($token) {

    //     $query = "DELETE FROM tokens WHERE token = ?";
        
    //     $statement = $this->conexion->prepare($query);
    //     $statement->bind_param("s", $token);
    //     $statement->execute();
    // }
}
?>
