<?php
require_once 'conexionBD.php';

class Token {
    private $conexion;

    public function __construct() {
        $this->conexion = connection::dbConnection();
    }
    //Generamos un token aleatorio
    private function generarToken() {
        return bin2hex(random_bytes(32)); 
    }
    //Agregamos un token en la base de datos mediante el id del usuario.
    public function agregarToken($usuarioID) {
        $duracionSesion = 3 * 60; 
    
        $token = $this->generarToken();
        $token_expiracion = date('Y-m-d H:i:s', time() + $duracionSesion);
    
        $query = "INSERT INTO token (token, fecha_expiracion, usuarioID) VALUES (?, ?, ?)";
        $declaramos = $this->conexion->prepare($query);
        $declaramos->bind_param("sss", $token, $token_expiracion, $usuarioID);
        $declaramos->execute();
    }
    //Verificamos si el token mas reciente aun no ha expirado, para ello debe comparar la fecha con la fecha actual.
    public function verificarToken($usuarioID) {
        $tiempo_actual = date('Y-m-d H:i:s');
        $query = "SELECT * FROM token WHERE usuarioID = ? ORDER BY fecha_expiracion DESC LIMIT 1";
        
        $declaramos = $this->conexion->prepare($query);
        $declaramos->bind_param("i", $usuarioID);
        $declaramos->execute();

        $result = $declaramos->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $token = $row['token'];
            $expiracion = $row['fecha_expiracion'];
            
            if ($expiracion > $tiempo_actual) {
                // echo "Token obtenido correctamente.\n";
                // echo "Token actual: $token\n";
                // echo "Fecha de expiración: $expiracion\n";
                return true; 
            } else {
                echo "El token ha expirado.";
                return false;
            }
        } else {
            echo "No se encontraron tokens para este usuario.";
            return false;
        }
    }
}
?>
