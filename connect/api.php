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
        $sql = "SELECT p.id_producto, p.id_marca, m.nombre_marca, p.id_modelo, mo.nombre_modelo, p.stock, p.precioUnidad, p.tallas
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
    private $productos;

    public function __construct() {
        $this->productos = array();
    }

    public function agregarProducto($id_producto, $cantidad) {
        if (array_key_exists($id_producto, $this->productos)) {
            $this->productos[$id_producto]['cantidad'] += $cantidad;
        } else {
            $this->productos[$id_producto] = array(
                'id_producto' => $id_producto,
                'cantidad' => $cantidad
            );
        }
    }


    public function vaciarCarrito() {
        $this->productos = array();
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
}elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_SERVER['REQUEST_URI'] === '/Proyecto/connect/api.php/carrito') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (isset($input['id_producto'], $input['cantidad'])) {
            $id_producto = $input['id_producto'];
            $cantidad = $input['cantidad'];

            $producto = new Producto();
            $productos = $producto->obtenerProductos();

            if (!is_array($productos)) {
                $response = array('mensaje' => 'Error: No se pudieron obtener los productos del inventario.');
            } else {
                $productoEnInventario = false;
                
                foreach ($productos as $prod) {
                    if ($prod['id_producto'] == $id_producto) {
                        $productoEnInventario = true;
                        break;
                    }
                }

                $carrito = new Carrito();

                if ($productoEnInventario) {
                    $carrito->agregarProducto($id_producto, $cantidad);
                    $producto_agregado = $carrito->obtenerUltimoProductoAgregado();

                    $response = array('mensaje' => 'Producto agregado al carrito exitosamente.', 'producto_agregado' => $producto_agregado);
                } else {
                    // Producto no encontrado en el inventario
                    $response = array('mensaje' => 'El producto no está disponible en el inventario.');
                }
            }
        } else {
            // Parámetros faltantes en la solicitud
            $response = array('mensaje' => 'Error: Se requieren id_producto y cantidad para agregar un producto al carrito.');
        }

        // Devolver la respuesta como JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}

?>
