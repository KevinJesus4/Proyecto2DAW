CREATE DATABASE IF NOT EXISTS kevStore;

USE kevStore;

CREATE TABLE IF NOT EXISTS Usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255),
    clave VARCHAR(255),
    email VARCHAR(255),
    UNIQUE KEY unique_usuario (nombre, clave, email)
);

CREATE TABLE IF NOT EXISTS Token (
    id_token INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    token VARCHAR(255),
    fecha_expiracion DATETIME
);

CREATE TABLE IF NOT EXISTS Cliente (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombreCli VARCHAR(255),
    apellido VARCHAR(255),
    emailCli VARCHAR(255),
    UNIQUE KEY unique_cliente (emailCli)
);

CREATE TABLE IF NOT EXISTS Carrito (
    id_carrito INT AUTO_INCREMENT PRIMARY KEY,
    cliente INT,
    marca INT,
    modelo INT,
    cantidad INT,
    precioUnidad DECIMAL(10, 2),
    estadoId INT
);

CREATE TABLE IF NOT EXISTS Producto (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    id_marca INT,
    id_modelo INT,
    stock INT,
    precioUnidad DECIMAL(10, 2),
    tallas VARCHAR(255),
    UNIQUE KEY unique_producto (id_marca, id_modelo)
);


CREATE TABLE IF NOT EXISTS EstadoPedido (
    estadoId INT AUTO_INCREMENT PRIMARY KEY,
    estado VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS Marca (
    id_marca INT AUTO_INCREMENT PRIMARY KEY,
    nombre_marca VARCHAR(255),
    UNIQUE KEY unique_marca (nombre_marca)
);

CREATE TABLE IF NOT EXISTS Modelo (
    id_modelo INT AUTO_INCREMENT PRIMARY KEY,
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
('Air Force 1'),
('Stan Smith');

INSERT INTO Producto (id_marca, id_modelo, stock, precioUnidad, tallas) VALUES
(1, 1, 10, 59.99, '36, 37, 38, 39, 40, 41, 42, 43, 44, 45'),
(2, 2, 15, 49.99, '36, 37, 38, 39, 40, 41, 42, 43, 44, 45');


ALTER TABLE Token
ADD CONSTRAINT fk_usuario_token FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario) ON DELETE CASCADE;

ALTER TABLE Carrito
ADD CONSTRAINT fk_carrito_cliente FOREIGN KEY (cliente) REFERENCES Cliente(id_cliente),
ADD CONSTRAINT fk_carrito_marca FOREIGN KEY (marca) REFERENCES Marca(id_marca),
ADD CONSTRAINT fk_carrito_modelo FOREIGN KEY (modelo) REFERENCES Modelo(id_modelo);

ALTER TABLE Producto
ADD CONSTRAINT fk_producto_marca FOREIGN KEY (id_marca) REFERENCES Marca(id_marca),
ADD CONSTRAINT fk_producto_modelo FOREIGN KEY (id_modelo) REFERENCES Modelo(id_modelo);


