<?php
require 'conexionBD.php';

class Usuario {

    private $conn;
    public function __construct() {
        $this->conn = connection::dbConnection();
    }

    public function obtenerUsuarios() {
        $sql = "SELECT * FROM usuario";
        $resultado = $this->conn->query($sql);
    
        if ($resultado->num_rows > 0) {
            $usuarios = array();
    
            while ($columna = $resultado->fetch_assoc()) {
                $usuarios[] = $columna;
            }
            echo json_encode($usuarios);
        } else {
            echo json_encode(array('mensaje' => 'No se encontraron usuarios'));
        }
    }
}



class Marca {

    private $conn;
    public function __construct() {
        $this->conn = connection::dbConnection();
    }

    public function obtenerMarcas() {
        $sql = "SELECT * FROM marca";
        $resultado = $this->conn->query($sql);
    
        if ($resultado->num_rows > 0) {
            $marcas = array();
    
            while ($columna = $resultado->fetch_assoc()) {
                $marcas[] = $columna;
            }
            echo json_encode($marcas);
        } else {
            echo json_encode(array('mensaje' => 'No se encontraron marcas'));
        }
    }
}


class Cliente {

    private $conn;
    public function __construct() {
        $this->conn = connection::dbConnection();
    }

    public function obtenerClientes() {
        $sql = "SELECT * FROM cliente";
        $resultado = $this->conn->query($sql);
    
        if ($resultado->num_rows > 0) {
            $clientes = array();
    
            while ($columna = $resultado->fetch_assoc()) {
                $clientes[] = $columna;
            }
            echo json_encode($clientes);
        } else {
            echo json_encode(array('mensaje' => 'No se encontraron clientes'));
        }
    }

    //Verificar si funciona (queremos solo actualizar el email.)
    public function registrarCliente($nombre, $apellido, $email) {
        $sql = "INSERT INTO cliente (nombreCli, apellido, emailCli) VALUES ('$nombre', '$apellido', '$email')";
        
        if ($this->conn->query($sql) === TRUE) {
            echo json_encode(array('mensaje' => 'Cliente registrado exitosamente'));
        } else {
            echo json_encode(array('mensaje' => 'Error al registrar cliente: ' . $this->conn->error));
        }
    }


    public function actualizarCliente($email) {
        $sql = "UPDATE INTO cliente (emailCli) VALUES ('$email')";
        
        if ($this->conn->query($sql) === TRUE) {
            echo json_encode(array('mensaje' => 'Email del cliente actualizado exitosamente'));
        } else {
            echo json_encode(array('mensaje' => 'Error al aactualizar el email del cliente: ' . $this->conn->error));
        }
    }
    
}


class Modelo {

    private $conn;
    public function __construct() {
        $this->conn = connection::dbConnection();
    }

    public function obtenerModelos() {
        $sql = "SELECT * FROM Modelo";
        $resultado = $this->conn->query($sql);
    
        if ($resultado->num_rows > 0) {
            $modelos = array();
    
            while ($columna = $resultado->fetch_assoc()) {
                $modelos[] = $columna;
            }
            echo json_encode($modelos);
        } else {
            echo json_encode(array('mensaje' => 'No se encontraron modelos'));
        }
    }
}

class Producto {

    private $conn;
    public function __construct() {
        $this->conn = Connection::dbConnection();
    }

    public function obtenerProductos() {
        $sql = "SELECT * FROM producto";
        $resultado = $this->conn->query($sql);
    
        if ($resultado->num_rows > 0) {
            $productos = array();
    
            while ($columna = $resultado->fetch_assoc()) {
                $productos[] = $columna;
            }
            echo json_encode($productos);
        } else {
            echo json_encode(array('mensaje' => 'No se encontraron productos'));
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_SERVER['REQUEST_URI'] === '/Proyecto/connect/api.php/usuario') {
        $usuario = new Usuario();
        $usuario->obtenerUsuarios();
    } elseif ($_SERVER['REQUEST_URI'] === '/Proyecto/connect/api.php/marca') {
        $marca = new Marca();
        $marca->obtenerMarcas();
    } elseif ($_SERVER['REQUEST_URI'] === '/Proyecto/connect/api.php/cliente') {
        $cliente = new Cliente();
        $cliente->obtenerClientes();
    } elseif ($_SERVER['REQUEST_URI'] === '/Proyecto/connect/api.php/modelo') {
        $modelo = new Modelo();
        $modelo->obtenerModelos();
    } elseif ($_SERVER['REQUEST_URI'] === '/Proyecto/connect/api.php/producto') {
        $producto = new Producto();
        $producto->obtenerProductos();
    }     
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'], $_POST['apellido'], $_POST['email'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $cliente = new Cliente(); 
    $cliente->registrarCliente($nombre, $apellido, $email);
}

//Posibles cambios
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_POST['nombre'], $_POST['apellido'], $_POST['email'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $cliente = new Cliente(); 
    $cliente->actualizarCliente($email);
}

?>
