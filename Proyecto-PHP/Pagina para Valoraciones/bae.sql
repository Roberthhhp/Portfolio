-- Crear base de datos
CREATE DATABASE IF NOT EXISTS sistema_votaciones;
USE sistema_votaciones;


-- Crear tabla usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    usuario VARCHAR(20) PRIMARY KEY,
    password VARCHAR(255) NOT NULL
);


-- La contraseña estará vacía, pero puede modificarse en el futuro
INSERT INTO usuarios (usuario, password) VALUES ('root', '');

-- Crear tabla productos
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- Crear tabla votos
CREATE TABLE IF NOT EXISTS votos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cantidad INT DEFAULT 0,
    idPr INT NOT NULL,
    idUs VARCHAR(20) NOT NULL,
    CONSTRAINT fk_votos_usu FOREIGN KEY (idUs) REFERENCES usuarios(usuario) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_votos_pro FOREIGN KEY (idPr) REFERENCES productos(id) 
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- Insertar datos en la tabla productos
INSERT INTO productos (nombre) VALUES 
('Laptop HP Pavilion 15'),
('Smartphone Samsung Galaxy S21'),
('Tablet Apple iPad Pro'),
('Auriculares Sony WH-1000XM4'),
('Cámara Canon EOS R5'),
('Smartwatch Garmin Forerunner 945'),
('Monitor LG UltraGear 27"'),
('Teclado Mecánico Logitech G Pro'),
('Mochila Antirrobo XD Design'),
('Disco Duro Externo Seagate 2TB');

-- Insertar datos en la tabla usuarios
INSERT INTO usuarios (usuario) VALUES 
('Alumno');