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
//*********************** */
    public function obtenerProductos() {
        $sql = "SELECT p.id_producto, p.id_marca, m.nombre_marca, p.id_modelo, mo.nombre_modelo, p.stock, p.precioUnidad
                FROM Producto p
                INNER JOIN Marca m ON p.id_marca = m.id_marca
                INNER JOIN Modelo mo ON p.id_modelo = mo.id_modelo";

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
        $sql = "UPDATE Producto SET precioUnidad = ? WHERE id_producto = ?";
        
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("di", $nuevo_precio, $id_producto); 
        $statement->execute();
        
        if ($statement->affected_rows > 0) {
            echo json_encode(array('mensaje' => 'Precio del producto actualizado exitosamente'));
        } else {
            echo json_encode(array('mensaje' => 'Error al actualizar el precio del producto'));
        }
    }
}


class Carrito {
    private $conn;

    public function __construct() {
        $this->conn = connection::dbConnection();
    }

    function agregarAlCarrito($cliente, $marca, $modelo, $cantidad) {
        try {
            $sql = "SELECT * FROM Carrito WHERE cliente = ? AND marca = ? AND modelo = ? LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iii", $cliente, $marca, $modelo);
            $stmt->execute();
            $productoEnCarrito = $stmt->get_result()->fetch_assoc();

            if ($productoEnCarrito) {
                // Si el producto ya está en el carrito, actualizar la cantidad
                $nuevaCantidad = $productoEnCarrito['cantidad'] + $cantidad;
                $sql = "UPDATE Carrito SET cantidad = ? WHERE cliente = ? AND marca = ? AND modelo = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("iiii", $nuevaCantidad, $cliente, $marca, $modelo);
                $stmt->execute();
            } else {
                // Si el producto no está en el carrito, insertarlo
                $sql = "INSERT INTO Carrito (cliente, marca, modelo, cantidad) VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("iiii", $cliente, $marca, $modelo, $cantidad);
                $stmt->execute();
            }

            // Actualizar el stock del producto en la tabla de productos
            $sql = "UPDATE Producto SET stock = stock - ? WHERE id_marca = ? AND id_modelo = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iii", $cantidad, $marca, $modelo);
            $stmt->execute();

            // Devolver una respuesta exitosa
            echo json_encode(array('mensaje' => 'Producto agregado al carrito exitosamente'));
        } catch (Exception $e) {
            // Manejar cualquier excepción que ocurra
            echo json_encode(array('error' => 'Error al agregar el producto al carrito: ' . $e->getMessage()));
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
    } elseif ($_SERVER['REQUEST_URI'] === '/Proyecto/connect/api.php/carrito') {
        $data = json_decode(file_get_contents('php://input'), true);
    
        if (isset($data['cliente'], $data['marca'], $data['modelo'], $data['cantidad'])) {
            $cliente = $data['cliente'];
            $marca = $data['marca'];
            $modelo = $data['modelo'];
            $cantidad = $data['cantidad'];
    
            // Instanciar la clase Carrito y agregar el producto
            $carrito = new Carrito();
            $carrito->agregarAlCarrito($cliente, $marca, $modelo, $cantidad);
    
            // Devolver una respuesta exitosa
            echo json_encode(array('mensaje' => 'Producto agregado al carrito exitosamente'));
        } else {
            // Devolver un mensaje de error si faltan los parámetros requeridos
            echo json_encode(array('error' => 'Se requieren cliente, marca, modelo y cantidad para agregar un producto al carrito.'));
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

?>
