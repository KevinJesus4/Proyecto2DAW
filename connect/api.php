<?php
require 'conexionBD.php';

class Usuario {

    private $conn;
    public function __construct() {
        $this->conn = connection::dbConnection();
    }
    // Funcion para seleccionar todos los usuarios y almacenarlos en una array.
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
            echo json_encode(array('mensaje' => 'No se han podido encontrar usuarios'));
        }
    }
}

class Marca {

    private $conn;
    public function __construct() {
        $this->conn = connection::dbConnection();
    }
    //Funcion para obtener todas las marcas, para ello lo realizamos con select *
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
            echo json_encode(array('mensaje' => 'No se han podido encontrar marcas'));
        }
    }
}

class Cliente {

    private $conn;
    public function __construct() {
        $this->conn = connection::dbConnection();
    }
    // Obtenemos todos los clientes, lo utilizaremos para que nos muestre en una tabla
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
            echo json_encode(array('mensaje' => 'No se han podido encontrar clientes'));
        }
    }
    // Agregamos un nuevo cliente, para ello debemos realizar validaciones.
    public function agregarCliente($nombreCli, $apellido, $emailCli) {
        if (empty($nombreCli) || empty($apellido) || empty($emailCli)) {
            http_response_code(400);
            echo json_encode(array('error' => 'Todos los campos deben ser obligatorios.'));
            return;
        }
        if (!filter_var($emailCli, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(array('error' => 'El formato del correo electrónico es inválido.'));
            return;
        }
        if (!preg_match("/^[a-zA-Z-' ]*$/", $nombreCli) || !preg_match("/^[a-zA-Z-' ]*$/", $apellido)) {
            http_response_code(400);
            echo json_encode(array('error' => 'Los campos de nombre y apellido no pueden contener caracteres especiales.'));
            return;
        }
    
        $sql = "INSERT INTO cliente (nombreCli, apellido, emailCli) VALUES (?, ?, ?)";
        $declarar = $this->conn->prepare($sql);
        $declarar->bind_param("sss", $nombreCli, $apellido, $emailCli);
        
        if ($declarar->execute()) {
            $mensaje = array('mensaje' => 'El cliente ha sido registrado con éxito');
            echo json_encode($mensaje);
            error_log("Cliente registrado exitosamente: " . json_encode($mensaje));
        } else {
            $mensaje = array('error' => 'Error al intentar registrar el cliente: ' . $declarar->error);
            echo json_encode($mensaje);
            error_log("Error al registrar el cliente: " . json_encode($mensaje));
        }
    }
    //Recibimos el id del cliente como parametro y lo eliminamos.
    public function eliminarCliente($clienteID) {
        $sql = "DELETE FROM Cliente WHERE id = ?";
        $declarar = $this->conn->prepare($sql);
        $declarar->bind_param("i", $clienteID); //entero
    
        if ($declarar->execute()) {
            $mensaje = array('mensaje' => 'El cliente ha sido eliminado con éxito');
            echo json_encode($mensaje);
            error_log("Cliente eliminado exitosamente: " . json_encode($mensaje));
        } else {
            $mensaje = array('error' => 'Error al intentar eliminar el cliente: ' . $declarar->error);
            echo json_encode($mensaje);
            error_log("Error al eliminar el cliente: " . json_encode($mensaje));
        }
    }
    
}

class Modelo {

    private $conn;
    public function __construct() {
        $this->conn = connection::dbConnection();
    }
    //Mostraremos una lista de los modelos disponibles.
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
    //Mostraremos detalles de un modelo en especifico a traves de su id.
    public function obtenerModeloPorID($modeloID) {
        $sql = "SELECT * FROM Modelo WHERE id = ?";
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("i", $modeloID);
        $statement->execute();
        
        $resultado = $statement->get_result();
        
        if ($resultado->num_rows > 0) {
            $modelo = $resultado->fetch_assoc();
            echo json_encode($modelo);
        } else {
            echo json_encode(array('mensaje' => 'Modelo no encontrado'));
        }
    }
    //Agregamos un nuevo modelo con el parametro nombre_modelo, antes debemos realizar validaciones.
    public function agregarModelo($nombre_modelo) {
        if(empty($nombre_modelo)) {
            echo json_encode(array('error' => 'El no puede estar vacío'));
            return;
        }
        if (!preg_match('/^[a-zA-Z0-9\s]+$/', $nombre_modelo)) {
            echo json_encode(array('error' => 'El nombre del modelo solo debe tener letras, números y espacios'));
            return;
        }
        
        $sql = "SELECT id FROM Modelo WHERE nombre_modelo = ?";
        $declarar = $this->conn->prepare($sql);
        $declarar->bind_param("s", $nombre_modelo);
        $declarar->execute();
        $declarar->store_result();
        if ($declarar->num_rows > 0) {
            echo json_encode(array('error' => 'Este modelo ya existe'));
            return;
        }
        
        $sql_insert = "INSERT INTO Modelo (nombre_modelo) VALUES (?)";
        $declarar_insert = $this->conn->prepare($sql_insert);
        $declarar_insert->bind_param("s", $nombre_modelo);
        if ($declarar_insert->execute()) {
            $modeloID = $declarar_insert->insert_id;
            echo json_encode(array('mensaje' => 'Modelo agregado correctamente', 'modeloID' => $modeloID));
        } else {
            echo json_encode(array('error' => 'Error al agregar el modelo: ' . $declarar_insert->error));
        }
    } 
}

class Producto {

    private $conn;
    public function __construct() {
        $this->conn = connection::dbConnection();
    }
    //Mostramos todos los productos.
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
    //Mostramos un producto en especifico  utilizando su id.
    public function obtenerProductoPorId($productoId) {
        $sql = "SELECT p.id, p.marcaID, m.nombre_marca, p.modeloID, mo.nombre_modelo, p.stock, p.precioUnidad
        FROM Producto p
        INNER JOIN Marca m ON p.marcaID = m.id
        INNER JOIN Modelo mo ON p.modeloID = mo.id
        WHERE p.id = ?";

        
        $declarar = $this->conn->prepare($sql);
        $declarar->bind_param("i", $productoId);
        $declarar->execute();
        
        $resultado = $declarar->get_result();
        
        if ($resultado->num_rows > 0) {
            $producto = $resultado->fetch_assoc();
            header('Content-Type: application/json');
            echo json_encode($producto);
        } else {
            header('Content-Type: application/json', true, 404);
            echo json_encode(array('mensaje' => 'Producto no encontrado'));
        }
    }
    //Agregamos un nuevo producto, antes de eso debemos realizar las validaciones.
    public function agregarProducto($marcaID, $modeloID, $stock, $precioUnidad) {

        if (empty($marcaID) || empty($modeloID) || empty($stock) || empty($precioUnidad) ||
            !is_numeric($marcaID) || !is_numeric($modeloID) || !is_numeric($stock) || !is_numeric($precioUnidad)) {
            echo json_encode(array('error' => 'Todos los campos son obligatorios y deben ser números'));
            return;
        }
        
        if ($marcaID <= 0 || $modeloID <= 0 || $stock <= 0 || $precioUnidad <= 0) {
            echo json_encode(array('error' => 'Todos los campos deben ser valores positivos'));
            return;
        }

        if (!preg_match('/^[0-9]+$/', $stock) || 
            !preg_match('/^[0-9]+(?:\.[0-9]+)?$/', $precioUnidad)) {
            echo json_encode(array('error' => 'Los campos contienen caracteres no válidos'));
            return;
        }
        $sql = "INSERT INTO Producto (marcaID, modeloID, stock, precioUnidad) VALUES (?, ?, ?, ?)";
        $declarar = $this->conn->prepare($sql);
        $declarar->bind_param("iiid", $marcaID, $modeloID, $stock, $precioUnidad);
        
        if ($declarar->execute()) {
            echo json_encode(array('mensaje' => 'Nuevo producto agregado exitosamente'));
        } else {
            echo json_encode(array('error' => 'Error al agregar el nuevo producto: ' . $declarar->error));
        }
    }
    //Actualizamos el precio de un producto, antes de ello debemos validar el campo.
    public function actualizarPrecio($productoID, $nuevo_precio) {

        if (!preg_match('/^[0-9]+(?:\.[0-9]+)?$/', $nuevo_precio)) {
            echo json_encode(array('error' => 'El nuevo precio contiene caracteres no válidos'));
            return;
        }

        $sql = "SELECT id FROM Producto WHERE id = ?";
        $declarar = $this->conn->prepare($sql);
        $declarar->bind_param("i", $productoID); 
        $declarar->execute();
        $declarar->store_result();
    
        if ($declarar->num_rows == 1) {
            $sql_actualizar = "UPDATE Producto SET precioUnidad = ? WHERE id = ?";
            $declarar_actualizar = $this->conn->prepare($sql_actualizar);
            $declarar_actualizar->bind_param("di", $nuevo_precio, $productoID); 
            $declarar_actualizar->execute();
            
            if ($declarar_actualizar->affected_rows > 0) {
                echo json_encode(array('mensaje' => 'El precio del producto se ha actualizado exitosamente'));
            } else {
                echo json_encode(array('error' => 'Error al tratar de actualizar el precio del producto'));
            }
        } else {
            echo json_encode(array('error' => 'El producto que estas indicando no existe'));
        }
    }
    //Utilizamos para actualizar el precio de un producto.
    public function actualizarStock($productoID, $nuevoStock) {
        $sql = "UPDATE Producto SET stock = ? WHERE id = ?";
        $declarar = $this->conn->prepare($sql);
        $declarar->bind_param("ii", $nuevoStock, $productoID);
        if ($declarar->execute()) {
            return true;
        } else {
            return false;
        }
    }
    //Elimina un producto mediante su id.
    public function eliminarProducto($productoID) {
        $sql = "DELETE FROM Producto WHERE id = ?";
        $declarar = $this->conn->prepare($sql);
        $declarar->bind_param("i", $productoID);

        if ($declarar->execute()) {
            echo json_encode(array('mensaje' => 'Producto eliminado exitosamente'));
        } else {
            echo json_encode(array('error' => 'Error al eliminar el producto: ' . $declarar->error));
        }
    }
    //Cuando eliminamos un carrito actualizara el stock del carrito, ya que devolvera la cantidad a stock.
    public function actualizarStockEliminarDelCarrito($productoID, $cantidad) {
        $sql = "UPDATE Producto SET stock = stock + ? WHERE id = ?";
        $declarar = $this->conn->prepare($sql);
        $declarar->bind_param("ii", $cantidad, $productoID);
        
        if ($declarar->execute()) {
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
    //Obtenemos todos los datos del carrito
    public function obtenerCarrito() {
        $sql = "SELECT c.id, cl.nombreCli AS cliente, m.nombre_marca, mo.nombre_modelo, c.cantidad, p.precioUnidad 
                FROM Carrito c
                INNER JOIN Cliente cl ON c.clienteID = cl.id
                INNER JOIN Producto p ON c.productoID = p.id
                INNER JOIN Marca m ON p.marcaID = m.id
                INNER JOIN Modelo mo ON p.modeloID = mo.id";
        
        $declarar = $this->conn->prepare($sql);
        if (!$declarar) {
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'Error de preparación de la consulta: ' . $this->conn->error));
            return;
        }
        
        $declarar->execute();
        $resultado = $declarar->get_result();
    
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
    //Obtenemos los datos de un carrito en especifico mediante su id
    public function obtenerCarritoPorId($carritoID) {
        $sql = "SELECT c.id AS carritoID, p.id AS productoID, p.marcaID, m.nombre_marca, p.modeloID, mo.nombre_modelo, c.cantidad, p.stock, p.precioUnidad
                FROM Carrito c
                INNER JOIN Producto p ON c.productoID = p.id
                INNER JOIN Marca m ON p.marcaID = m.id
                INNER JOIN Modelo mo ON p.modeloID = mo.id
                WHERE c.id = ?";
        
        $statement = $this->conn->prepare($sql);
        $statement->bind_param("i", $carritoID);
        $statement->execute();
        
        $resultado = $statement->get_result();
        
        if ($resultado->num_rows > 0) {
            $carrito = $resultado->fetch_assoc();
            header('Content-Type: application/json');
            echo json_encode($carrito);
        } else {
            header('Content-Type: application/json', true, 404);
            echo json_encode(array('mensaje' => 'Carrito no encontrado'));
        }
    }
    //Eliminamos uno o varios carritos segun su id.
    public function eliminarProductosDelCarrito($productosIDs) {
        if (empty($productosIDs)) {
            http_response_code(400); 
            echo json_encode(array('error' => 'No se proporcionaron IDs de productos para eliminar.'));
            return;
        }
    
        $marcadorP = implode(',', array_fill(0, count($productosIDs), '?'));
        $sqlSelect = "SELECT productoID, cantidad FROM Carrito WHERE id IN ($marcadorP)";
        $declararSelect = $this->conn->prepare($sqlSelect);
    
        if (!$declararSelect) {
            http_response_code(500);
            echo json_encode(array('error' => 'Error de preparación de la consulta: ' . $this->conn->error));
            return;
        }
    
        $tipos = str_repeat('i', count($productosIDs));
        $declararSelect->bind_param($tipos, ...$productosIDs);
        $declararSelect->execute();
        $resultSelect = $declararSelect->get_result();
        $productosActualizarStock = array();
    
        while ($fila = $resultSelect->fetch_assoc()) {
            $productoID = $fila['productoID'];
            $cantidad = $fila['cantidad'];
            $productosActualizarStock[$productoID] = $cantidad;
        }
    
        $sqlEliminar = "DELETE FROM Carrito WHERE id IN ($marcadorP)";
        $declaEliminacion = $this->conn->prepare($sqlEliminar);
        if (!$declaEliminacion) {
            http_response_code(500);
            echo json_encode(array('error' => 'Error de preparación de la consulta: ' . $this->conn->error));
            return;
        }
    
        $declaEliminacion->bind_param($tipos, ...$productosIDs);
        if ($declaEliminacion->execute()) {
            foreach ($productosActualizarStock as $productoID => $cantidad) {
                $producto = new Producto();
                $producto->actualizarStockEliminarDelCarrito($productoID, $cantidad);
            }
    
            http_response_code(200); 
            echo json_encode(array('mensaje' => 'Productos eliminados del carrito correctamente'));
        } else {
            http_response_code(500); 
            echo json_encode(array('error' => 'Error al eliminar los productos del carrito: ' . $declaEliminacion->error));
        }
    }
    //Agregamos un producto al carrito, para ello debe verificar el stock.
    function agregarAlCarrito($clienteID, $productoID, $cantidad) {
        
        $sqlStock = "SELECT stock FROM Producto WHERE id = ?";
        $declararStock = $this->conn->prepare($sqlStock);
        $declararStock->bind_param("i", $productoID);
        $declararStock->execute();
        $resultStock = $declararStock->get_result();
    
        if ($resultStock->num_rows > 0) {
            $row = $resultStock->fetch_assoc();
            $stockActual = $row['stock'];
    
            if ($stockActual >= $cantidad) {
                
                $nuevoStock = $stockActual - $cantidad;    
                $producto = new Producto();
                $producto->actualizarStock($productoID, $nuevoStock);
    
                $sql = "INSERT INTO carrito (clienteID, productoID, cantidad) 
                        VALUES (?, ?, ?)";
    
                $declarar = $this->conn->prepare($sql);
                $declarar->bind_param("iii", $clienteID, $productoID, $cantidad);
    
                if ($declarar->execute()) {
                    http_response_code(200); 
                    echo json_encode(array('mensaje' => 'Producto agregado al carrito correctamente'));
                } else {
                    http_response_code(500); 
                    echo json_encode(array('error' => 'Error al agregar el producto al carrito: ' . $declarar->error));
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
    //Obtenemos detalles de un carrito en concreto mediante la id.
    public function obtenerDetallesCarrito($carritoID) {
        $sql = "SELECT c.id, cl.nombreCli AS cliente, m.nombre_marca, mo.nombre_modelo, c.cantidad, p.precioUnidad 
        FROM Carrito c
        INNER JOIN Cliente cl ON c.clienteID = cl.id
        INNER JOIN Producto p ON c.productoID = p.id
        INNER JOIN Marca m ON p.marcaID = m.id
        INNER JOIN Modelo mo ON p.modeloID = mo.id
        WHERE c.id = ?";
        
        $declarar = $this->conn->prepare($sql);
        $declarar->bind_param("i", $carritoID);
        
        if ($declarar->execute()) {
            $result = $declarar->get_result();
            $carrito = $result->fetch_assoc();
            if ($carrito) {
                return $carrito;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}

//----------------------GET----------------------
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
    } elseif (preg_match('/\/Proyecto\/connect\/api.php\/modelo\/(\d+)/', $_SERVER['REQUEST_URI'], $matches)) {
        $modeloID = $matches[1];
        $modelo = new Modelo();
        $modelo->obtenerModeloPorID($modeloID);
    } elseif ($_SERVER['REQUEST_URI'] === '/Proyecto/connect/api.php/producto') {
        $producto = new Producto();
        $producto->obtenerProductos();
    } elseif (preg_match('/\/Proyecto\/connect\/api.php\/producto\/(\d+)/', $_SERVER['REQUEST_URI'], $matches)) {
        $productoID = $matches[1];
        $producto = new Producto();
        $producto->obtenerProductoPorId($productoID);
    } else if ($_SERVER['REQUEST_URI'] === '/Proyecto/connect/api.php/carrito') {
        $carrito = new Carrito();
        $carrito->obtenerCarrito();
    } elseif (preg_match('/\/Proyecto\/connect\/api.php\/carrito\/detalles\/(\d+)/', $_SERVER['REQUEST_URI'], $matches)) {
        $carritoID = $matches[1];
        $carrito = new Carrito();
        $detallesCarrito = $carrito->obtenerDetallesCarrito($carritoID);
    
        if ($detallesCarrito) {
            echo json_encode($detallesCarrito);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'El carrito no fue encontrado.'));
        }
    } elseif (preg_match('/\/Proyecto\/connect\/api.php\/carrito\/(\d+)/', $_SERVER['REQUEST_URI'], $matches)) {
        $carritoID = $matches[1];
        $carrito = new Carrito();
        $carrito->obtenerCarritoPorId($carritoID);
    }
}
//----------------------POST----------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_SERVER['REQUEST_URI'] === '/Proyecto/connect/api.php/cliente') {
        $entradaDatos = json_decode(file_get_contents('php://input'), true);
        var_dump($entradaDatos);

        if (isset($entradaDatos['nombreCli'], $entradaDatos['apellido'], $entradaDatos['emailCli'])) {
            $nombreCli = $entradaDatos['nombreCli'];
            $apellido = $entradaDatos['apellido'];
            $emailCli = $entradaDatos['emailCli'];
            $cliente = new Cliente();
            $cliente->agregarCliente($nombreCli, $apellido, $emailCli);
        }
    } else if ($_SERVER['REQUEST_URI'] === '/Proyecto/connect/api.php/carrito') {
        $entradaDatos = json_decode(file_get_contents('php://input'), true);

        if (isset($entradaDatos['clienteID'], $entradaDatos['productoID'], $entradaDatos['cantidad'])) {       
            $clienteID = $entradaDatos['clienteID'];
            $productoID = $entradaDatos['productoID'];
            $cantidad = $entradaDatos['cantidad'];
            $carrito = new Carrito();
            $carrito->agregarAlCarrito($clienteID, $productoID, $cantidad);
        } else {
            echo json_encode(array('error' => 'Se requieren clienteID, productoID y cantidad para agregar un producto al carrito.'));
        }
    } elseif ($_SERVER['REQUEST_URI'] === '/Proyecto/connect/api.php/modelo') {
        $entradaDatos = json_decode(file_get_contents('php://input'), true);
    
        if (isset($entradaDatos['nombre_modelo'])) {
            $nombre_modelo = $entradaDatos['nombre_modelo'];
            $modelo = new Modelo();
            $modelo->agregarModelo($nombre_modelo);
        } else {
            echo json_encode(array('error' => 'Se requiere el nombre del modelo para agregar un nuevo modelo.'));
        }
    } elseif ($_SERVER['REQUEST_URI'] === '/Proyecto/connect/api.php/producto') {
        $entradaDatos = json_decode(file_get_contents('php://input'), true);

        if (isset($entradaDatos['marcaID'], $entradaDatos['modeloID'], $entradaDatos['stock'], $entradaDatos['precioUnidad'])) {       
            $marcaID = $entradaDatos['marcaID'];
            $modeloID = $entradaDatos['modeloID'];
            $stock = $entradaDatos['stock'];
            $precioUnidad = $entradaDatos['precioUnidad'];
            $producto = new Producto();
            $producto->agregarProducto($marcaID, $modeloID, $stock, $precioUnidad);
        } else {
            echo json_encode(array('error' => 'Se requieren marcaID, modeloID, stock y precioUnidad para agregar un nuevo producto.'));
        }
    } else {
        echo json_encode(array('error' => 'La ruta solicitada no existe.'));
    }
}
//----------------------PUT----------------------
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if ($_SERVER['REQUEST_URI'] === '/Proyecto/connect/api.php/producto') {
        $entradaDatos = json_decode(file_get_contents('php://input'), true); 
        
        if (isset($entradaDatos['productoID'], $entradaDatos['precioUnidad'])) {
            $productoID = $entradaDatos['productoID'];
            $nuevo_precio = $entradaDatos['precioUnidad'];
            $producto = new Producto();
            $producto->actualizarPrecio($productoID, $nuevo_precio);
        } else {
            $producto = new Producto();
            $producto->obtenerProductos();
        }
    }
}
//----------------------DELETE----------------------
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if ($_SERVER['REQUEST_URI'] === '/Proyecto/connect/api.php/carrito') {
        $entradaDatos = file_get_contents('php://input');
        $info = json_decode($entradaDatos, true);
        
        if (isset($info['productosIDs'])) {
            $productosIDs = $info['productosIDs'];
            $carrito = new Carrito();
            $carrito->eliminarProductosDelCarrito($productosIDs);
        } else {
            http_response_code(400);
            echo json_encode(array('error' => 'No se proporcionaron los IDs de los productos a eliminar'));
        }
    } elseif ($_SERVER['REQUEST_URI'] === '/Proyecto/connect/api.php/producto') {
        $entradaDatos = file_get_contents('php://input');
        $info = json_decode($entradaDatos, true);

        if (isset($info['productoID'])) {
            $productoID = $info['productoID'];
            $producto = new Producto();
            $producto->eliminarProducto($productoID);
        } else {
            http_response_code(400);
            echo json_encode(array('error' => 'No se proporcionó el ID del producto a eliminar'));
        }
    } elseif ($_SERVER['REQUEST_URI'] === '/Proyecto/connect/api.php/cliente') {
        $entradaDatos = file_get_contents('php://input');
        $info = json_decode($entradaDatos, true);

        if (isset($info['clienteID'])) {
            $clienteID = $info['clienteID'];
            $cliente = new Cliente();
            $cliente->eliminarCliente($clienteID);
        } else {
            http_response_code(400);
            echo json_encode(array('error' => 'No se proporcionó el ID del cliente a eliminar'));
        }
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'La ruta solicitada no existe'));
    }
}
?>
