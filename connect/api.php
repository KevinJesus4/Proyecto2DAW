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
        $sql = "INSERT INTO cliente (nombreCli, apellido, emailCli) VALUES (?, ?, ?)";
        
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("sss", $nombre, $apellido, $email);
        
        if ($statement->execute()) {
            echo json_encode(array('mensaje' => 'Cliente registrado exitosamente'));
        } else {
            echo json_encode(array('mensaje' => 'Error al registrar cliente: ' . $statement->error));
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
        $this->conn = connection::dbConnection();
    }
    public function obtenerProductos() {
        $sql = "SELECT p.id, p.marcaID, m.nombre_marca, p.modeloID, mo.nombre_modelo, p.stock, p.precioUnidad
                FROM Producto p
                INNER JOIN Marca m ON p.marcaID = m.id
                INNER JOIN Modelo mo ON p.modeloID = mo.id";

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
   

    //Dudas ------------------
    public function actualizarPrecio($id_producto, $nuevo_precio) {
        $sql = "UPDATE Producto SET precioUnidad = ? WHERE id = ?";
        
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("di", $nuevo_precio, $id_producto); 
        $statement->execute();
        
        if ($statement->affected_rows > 0) {
            echo json_encode(array('mensaje' => 'Precio del producto actualizado exitosamente'));
        } else {
            echo json_encode(array('mensaje' => 'Error al actualizar el precio del producto'));
        }
    }

    public function actualizarStock($productoID, $nuevoStock) {
        $sql = "UPDATE Producto SET stock = ? WHERE id = ?";
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("ii", $nuevoStock, $productoID);
        if ($statement->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
}


class Carrito {
    private $conn;

    public function __construct() {
        $this->conn = connection::dbConnection();
    }

    public function obtenerCarrito() {
        $sql = "SELECT c.id, c.clienteID, m.nombre_marca, mo.nombre_modelo, c.cantidad, p.precioUnidad 
                FROM Carrito c
                INNER JOIN Producto p ON c.productoID = p.id
                INNER JOIN Marca m ON p.marcaID = m.id
                INNER JOIN Modelo mo ON p.modeloID = mo.id";
    
        $statement = $this->conn->prepare($sql);
        if (!$statement) {
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'Error de preparación de la consulta: ' . $this->conn->error));
            return;
        }
        
        $statement->execute();
        $resultado = $statement->get_result();
    
        if ($resultado->num_rows > 0) {
            $carritos = array();
            while ($fila = $resultado->fetch_assoc()) {
                $carritos[] = $fila;
            }
            header('Content-Type: application/json');
            echo json_encode($carritos);
        } else {
            header('Content-Type: application/json');
            echo json_encode(array('mensaje' => 'No se encontraron carritos'));
        }
    }
    
    function agregarAlCarrito($clienteID, $productoID, $cantidad) {
        
        $sqlStock = "SELECT stock FROM Producto WHERE id = ?";
        $statementStock = $this->conn->prepare($sqlStock);
        $statementStock->bind_param("i", $productoID);
        $statementStock->execute();
        $resultStock = $statementStock->get_result();
    
        if ($resultStock->num_rows > 0) {
            $row = $resultStock->fetch_assoc();
            $stockActual = $row['stock'];
    
            if ($stockActual >= $cantidad) {
                
                $nuevoStock = $stockActual - $cantidad;    
                $producto = new Producto();
                $producto->actualizarStock($productoID, $nuevoStock);
    
                $sql = "INSERT INTO carrito (clienteID, productoID, cantidad) 
                        VALUES (?, ?, ?)";
    
                $statement = $this->conn->prepare($sql);
                $statement->bind_param("iii", $clienteID, $productoID, $cantidad);
    
                if ($statement->execute()) {
                    http_response_code(200); 
                    echo json_encode(array('mensaje' => 'Producto agregado al carrito correctamente'));
                } else {
                    http_response_code(500); 
                    echo json_encode(array('error' => 'Error al agregar el producto al carrito: ' . $statement->error));
                }
            } else {
                http_response_code(400); 
                echo json_encode(array('error' => 'No hay suficiente stock para la cantidad seleccionada'));
            }
        } else {
            http_response_code(404); 
            echo json_encode(array('error' => 'No se encontró el producto'));
        }
    }
    
    public function eliminarDelCarrito($carritoID) {
        $sql = "DELETE FROM Carrito WHERE id = ?";
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("i", $carritoID);
        
        if ($statement->execute()) {
            http_response_code(200);
            echo json_encode(array('mensaje' => 'Producto eliminado del carrito correctamente'));
        } else {
            http_response_code(500);
            echo json_encode(array('error' => 'Error al eliminar el producto del carrito: ' . $statement->error));
        }
    } 
}







//GET 

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
    }elseif ($_SERVER['REQUEST_URI'] === '/Proyecto/connect/api.php/carrito') {
        $producto = new Carrito();
        $producto->obtenerCarrito();
    }   
}


//POST

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_SERVER['REQUEST_URI'] === '/Proyecto/connect/api.php/cliente') {
        $input = json_decode(file_get_contents('php://input'), true);

        if (isset($input['nombre'], $input['apellido'], $input['email'])) {
            $nombre = $input['nombre'];
            $apellido = $input['apellido'];
            $email = $input['email'];

            $cliente = new Cliente();
            $cliente->registrarCliente($nombre, $apellido, $email);
        } else {
            echo json_encode(array('error' => 'Se requieren nombre, apellido y email para registrar un cliente.'));
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($_SERVER['REQUEST_URI'] === '/Proyecto/connect/api.php/carrito') {
            $input = json_decode(file_get_contents('php://input'), true);
    
           
            if (isset($input['clienteID'], $input['productoID'], $input['cantidad'])) {
                
                $clienteID = $input['clienteID'];
                $productoID = $input['productoID'];
                $cantidad = $input['cantidad'];
    
                $carrito = new Carrito();
                $carrito->agregarAlCarrito($clienteID, $productoID, $cantidad);
            } else {
                echo json_encode(array('error' => 'Se requieren clienteID, productoID y cantidad para agregar un producto al carrito.'));
            }
        } 
    }
     
}

//PUT

//Dudas ------------------
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if ($_SERVER['REQUEST_URI'] === '/Proyecto/connect/api.php/producto') {
      
        $input = json_decode(file_get_contents('php://input'), true); 
        if (isset($input['id_producto'], $input['precioUnidad'])) {
            $id_producto = $input['id_producto'];
            $nuevo_precio = $input['precioUnidad'];
        
            $producto = new Producto();
            $producto->actualizarPrecio($id_producto, $nuevo_precio);
        } else {
            $producto = new Producto();
            $producto->obtenerProductos();
        }
    }
}


//DELETE

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Verificar la URI de la solicitud DELETE
    if ($_SERVER['REQUEST_URI'] === '/Proyecto/connect/api.php/carrito') {
        // Leer el cuerpo de la solicitud en formato JSON
        $inputJSON = file_get_contents('php://input');
        // Decodificar el JSON en un array asociativo
        $data = json_decode($inputJSON, true);
        // Verificar si el ID del producto está presente en el array
        if (isset($data['id'])) {
            // Obtener el ID del producto
            $carritoID = $data['id'];
            // Llamar a la función para eliminar el producto del carrito
            $carrito = new Carrito();
            $carrito->eliminarDelCarrito($carritoID);
        } else {
            // Si no se proporcionó el ID del producto, devolver un error
            http_response_code(400);
            echo json_encode(array('error' => 'No se proporcionó el ID del producto'));
        }
    } else {
        // Si la URI de la solicitud DELETE no es la esperada, devolver un error
        http_response_code(404);
        echo json_encode(array('error' => 'La ruta solicitada no existe'));
    }
}




?>
