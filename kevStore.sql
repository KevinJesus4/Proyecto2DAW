CREATE DATABASE IF NOT EXISTS kevStore;

USE kevStore;

CREATE TABLE IF NOT EXISTS Usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255),
    clave VARCHAR(255),
    email VARCHAR(255),
    UNIQUE KEY unique_usuario (nombre, clave, email)
);

CREATE TABLE IF NOT EXISTS Token (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuarioID INT,
    token VARCHAR(255),
    fecha_expiracion DATETIME
);

CREATE TABLE IF NOT EXISTS Cliente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombreCli VARCHAR(255),
    apellido VARCHAR(255),
    emailCli VARCHAR(255),
    UNIQUE KEY unique_cliente (emailCli)
);

CREATE TABLE IF NOT EXISTS Carrito (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clienteID INT,
	productoID INT,
    cantidad INT,
    tallas VARCHAR(255),
    precioUnidad DECIMAL(10, 2),
    estadoID INT
);

CREATE TABLE IF NOT EXISTS Producto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marcaID INT,
    modeloID INT,
    stock INT,
    precioUnidad DECIMAL(10, 2),
    UNIQUE KEY unique_producto (marcaID, modeloID)
);


CREATE TABLE IF NOT EXISTS EstadoPedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estado VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS Marca (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_marca VARCHAR(255),
    UNIQUE KEY unique_marca (nombre_marca)
);

CREATE TABLE IF NOT EXISTS Modelo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_modelo VARCHAR(255),
    UNIQUE KEY unique_modelo (nombre_modelo)
);

INSERT INTO Usuario (nombre, clave, email) VALUES
('Kevin', '1234', 'kevinborja@example.com');

INSERT INTO Cliente (nombreCli, apellido, emailCli) VALUES
('Juan', 'Pérez', 'juan@example.com'),
('María', 'Gómez', 'maria@example.com');


INSERT INTO Marca (nombre_marca) VALUES
('Nike'),
('Adidas');


INSERT INTO Modelo (nombre_modelo) VALUES
('Air Monarch IV'),
('Air Force 1 Max'),
('Air Max'),
('Gazelle'),
('Forum'),
('Rivalry');

INSERT INTO Producto (marcaID, modeloID, stock, precioUnidad) VALUES
(1, 1, 10, 99.99),
(1, 2, 30, 89.99),
(1, 3, 45, 79.99),
(2, 1, 56, 59.99),
(2, 2, 34, 79.99),
(2, 3, 13, 49.99);


ALTER TABLE Token
ADD CONSTRAINT fk_usuario_token FOREIGN KEY (usuarioID) REFERENCES Usuario(ID) ON DELETE CASCADE;

ALTER TABLE Carrito
ADD CONSTRAINT fk_carrito_cliente FOREIGN KEY (clienteID) REFERENCES Cliente(id),
ADD CONSTRAINT fk_carrito_producto FOREIGN KEY (productoID) REFERENCES Producto(id);


ALTER TABLE Producto
ADD CONSTRAINT fk_producto_marca FOREIGN KEY (marcaID) REFERENCES Marca(id),
ADD CONSTRAINT fk_producto_modelo FOREIGN KEY (modeloID) REFERENCES Modelo(id);
