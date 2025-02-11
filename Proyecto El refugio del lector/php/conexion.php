<?php
/*
SQL:
SET GLOBAL log_bin_trust_function_creators = 1;
DROP TRIGGER IF EXISTS actualizar_tipo_vendedor;

CREATE TABLE libros_cliente_vendedor_copia AS SELECT * FROM libros_cliente_vendedor;
*/
/*
CREATE TABLE libros_cliente_vendedor_copia (
    libro_vendedor_id INT NOT NULL,
    vendedor_id INT NOT NULL,
    titulo VARCHAR(50) NOT NULL,
    isbn CHAR(13) NOT NULL,
    precio DECIMAL(5,2) NOT NULL CHECK (precio > 0),
    stock INT NOT NULL DEFAULT 0 CHECK (stock >= 0),
    PRIMARY KEY (libro_vendedor_id),
    CONSTRAINT fk_vendedor_id FOREIGN KEY (vendedor_id) REFERENCES cliente_vendedor (vendedor_id)
);
ALTER TABLE libros_cliente_vendedor_copia
MODIFY COLUMN libro_vendedor_id INT NOT NULL AUTO_INCREMENT;

*/ 
// Conexión PDO
$hostDB = '127.0.0.1';
$nombreDB = 'el_refugio_del_lector';
$usuarioDB = 'root';
$contrasnyaDB = '';

try {
    // Crear conexión
    $miPDO = new PDO("mysql:host=$hostDB", $usuarioDB, $contrasnyaDB);
    $miPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Crear base de datos si no existe
    $miPDO->exec("CREATE DATABASE IF NOT EXISTS $nombreDB");
    $miPDO->exec("USE $nombreDB");

    // Crear tablas
    $scriptTablas = <<<SQL
    -- Aquí copia las sentencias para crear todas las tablas
    -- Incluye las FOREIGN KEY necesarias y demás
            SET FOREIGN_KEY_CHECKS = 0;

        -- Eliminamos tablas hijas primero, luego las tablas padres
        DROP TABLE IF EXISTS ventas_detalle;
        DROP TABLE IF EXISTS libro_detalle;
        DROP TABLE IF EXISTS categoria_detalle;
        DROP TABLE IF EXISTS ventas;
        DROP TABLE IF EXISTS cliente_vendedor;
        DROP TABLE IF EXISTS libros_cliente_vendedor;

        DROP TABLE IF EXISTS autor;
        DROP TABLE IF EXISTS libro;
        DROP TABLE IF EXISTS editorial;
        DROP TABLE IF EXISTS cliente;
        DROP TABLE IF EXISTS categoria;

        -- Reactivamos las verificaciones de claves foráneas
        SET FOREIGN_KEY_CHECKS = 1;


        -- Aquí va todo el script SQL que compartiste

        -- Tabla para autores
        CREATE TABLE autor (
            autor_id INT NOT NULL AUTO_INCREMENT,
            nombre VARCHAR(50) NOT NULL,
            apellido VARCHAR(50) NOT NULL,
            nacionalidad VARCHAR(50) NOT NULL,
            PRIMARY KEY (autor_id)
        );

        -- Tabla para editoriales
        CREATE TABLE editorial (
            editorial_id INT NOT NULL AUTO_INCREMENT,
            editorial VARCHAR(50) NOT NULL,
            pais VARCHAR(50) NOT NULL,
            ruc CHAR(11) NOT NULL,
            direccion VARCHAR(100) NOT NULL,
            correo VARCHAR(50) NOT NULL,
            telefono VARCHAR(20) NOT NULL,
            contrasenia VARCHAR(20) NOT NULL,
            PRIMARY KEY (editorial_id),
            UNIQUE (editorial),
            UNIQUE (ruc),
            UNIQUE (correo),
            UNIQUE (telefono)
        );

        -- Tabla para libros en la librería
        CREATE TABLE libro (
            libro_id INT NOT NULL AUTO_INCREMENT,
            titulo VARCHAR(50) NOT NULL,
            isbn CHAR(13) NOT NULL,
            precio DECIMAL(5,2) NOT NULL CHECK (precio > 0),
            editorial_id INT NOT NULL,
            stock INT NOT NULL DEFAULT 0 CHECK (stock >= 0),
            PRIMARY KEY (libro_id),
            UNIQUE (titulo, editorial_id),
            UNIQUE (isbn),
            CONSTRAINT fk_editorial_id FOREIGN KEY (editorial_id) REFERENCES editorial (editorial_id)
        );

        -- Relación de libros con autores
        CREATE TABLE libro_detalle (
            libro_detalle_id INT NOT NULL AUTO_INCREMENT,
            libro_id INT NOT NULL,
            autor_id INT NOT NULL,
            PRIMARY KEY (libro_detalle_id),
            UNIQUE (libro_id, autor_id),
            CONSTRAINT fk_libro_id FOREIGN KEY (libro_id) REFERENCES libro (libro_id),
            CONSTRAINT fk_autor_id FOREIGN KEY (autor_id) REFERENCES autor (autor_id)
        );

        -- Categorías de libros
        CREATE TABLE categoria (
            categoria_id INT NOT NULL AUTO_INCREMENT,
            categoria ENUM('ficción', 'no ficción', 'infantil', 'juvenil', 'cómic y manga', 'english books', 'llibres en català') NOT NULL,
            PRIMARY KEY (categoria_id)
        );

        -- Relación de categorías con libros
        CREATE TABLE categoria_detalle (
            categoria_detalle_id INT NOT NULL AUTO_INCREMENT,
            categoria_id INT NOT NULL,
            libro_id INT NOT NULL,
            PRIMARY KEY (categoria_detalle_id),
            UNIQUE (categoria_id, libro_id),
            CONSTRAINT fk_categoria_id FOREIGN KEY (categoria_id) REFERENCES categoria (categoria_id),
            CONSTRAINT fk_libro_id_categoria_detalle FOREIGN KEY (libro_id) REFERENCES libro (libro_id)
        );

        -- Tabla para clientes (no vendedores)
        CREATE TABLE cliente (
            cliente_id INT NOT NULL AUTO_INCREMENT,
            nombre VARCHAR(50) NOT NULL,
            apellido VARCHAR(50) NOT NULL,
            telefono CHAR(20) NOT NULL,
            direccion VARCHAR(50) DEFAULT '',
            correo VARCHAR(50) UNIQUE ,
            contrasenia VARCHAR(8) NOT NULL,
            PRIMARY KEY (cliente_id),
            UNIQUE (correo)
        );

        -- Tabla para ventas
        CREATE TABLE ventas (
            ventas_id INT NOT NULL AUTO_INCREMENT,
            fecha DATE NOT NULL,
            cliente_id INT NOT NULL,
            PRIMARY KEY (ventas_id),
            CONSTRAINT fk_cliente_id FOREIGN KEY (cliente_id) REFERENCES cliente (cliente_id)
        );

        -- Tabla para detalles de ventas
        CREATE TABLE ventas_detalle (
            ventas_detalle_id INT NOT NULL AUTO_INCREMENT,
            cantidad INT NOT NULL CHECK (cantidad > 0),
            precio_final DECIMAL(5,2) NOT NULL CHECK (precio_final >= 0),
            libro_id INT NOT NULL,
            ventas_id INT NOT NULL,
            tipo_vendedor ENUM('librería', 'vendedor') NOT NULL,
            PRIMARY KEY (ventas_detalle_id),
            CONSTRAINT fk_libro_id_ventas_detalle FOREIGN KEY (libro_id) REFERENCES libro (libro_id),
            CONSTRAINT fk_ventas_id FOREIGN KEY (ventas_id) REFERENCES ventas (ventas_id)
        );

        -- Tabla para clientes vendedores (independiente de la tabla cliente)
        CREATE TABLE cliente_vendedor (
            vendedor_id INT NOT NULL AUTO_INCREMENT,
            nombre VARCHAR(50) NOT NULL, -- En lugar de referirse al cliente_id, se almacenan los datos directamente.
            apellido VARCHAR(50) NOT NULL,
            telefono CHAR(20) NOT NULL,
            direccion VARCHAR(50) DEFAULT '',
            correo VARCHAR(50) UNIQUE,
            contrasenia VARCHAR(8) NOT NULL,
            tipo_vendedor ENUM('profesional', 'particular') NOT NULL DEFAULT 'particular',
            total_libros_vendidos INT NOT NULL DEFAULT 0,
            PRIMARY KEY (vendedor_id),
            UNIQUE (correo) -- El correo debe ser único para cada vendedor
        );

        -- Tabla para los libros que venden los clientes vendedores
        CREATE TABLE libros_cliente_vendedor (
            libro_vendedor_id INT NOT NULL AUTO_INCREMENT,
            vendedor_id INT NOT NULL,
            titulo VARCHAR(50) NOT NULL,
            isbn CHAR(13) NOT NULL,
            precio DECIMAL(5,2) NOT NULL CHECK (precio > 0),
            stock INT NOT NULL DEFAULT 0 CHECK (stock >= 0),
            PRIMARY KEY (libro_vendedor_id),
            CONSTRAINT fk_vendedor_id FOREIGN KEY (vendedor_id) REFERENCES cliente_vendedor (vendedor_id)
        );

    SQL;
    $miPDO->exec($scriptTablas);

    // Crear el trigger
    $scriptTrigger = <<<SQL
    CREATE TRIGGER actualizar_tipo_vendedor
    AFTER INSERT ON ventas_detalle
    FOR EACH ROW
    BEGIN
        UPDATE cliente_vendedor
        SET total_libros_vendidos = total_libros_vendidos + NEW.cantidad,
            tipo_vendedor = CASE
                WHEN total_libros_vendidos + NEW.cantidad > 20 THEN 'profesional'
                ELSE 'particular'
            END
        WHERE vendedor_id = (
            SELECT vendedor_id 
            FROM libros_cliente_vendedor 
            WHERE libro_vendedor_id = NEW.libro_id
        );
    END;
    SQL;

    $miPDO->exec($scriptTrigger);

    echo "Base de datos, tablas y trigger creados correctamente.\n";
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
try {
    $scriptInsert = <<<SQL
    -- Inserta los datos de ejemplo
    -- Primero en las tablas principales (autor, editorial, cliente, etc.)
    -- Después en las tablas relacionadas (ventas, ventas_detalle, etc.)
    -- Asegúrate de seguir el orden correcto para respetar las claves foráneas
        -- Insertar datos para autores
        INSERT INTO categoria (categoria) VALUES
            ('ficción'),
            ('no ficción'),
            ('infantil'),
            ('juvenil'),
            ('cómic y manga'),
            ('english books'),
            ('llibres en català');

        -- 1. Insertar datos para autores
        INSERT INTO autor (nombre, apellido, nacionalidad) VALUES
            ('Gabriel', 'García Márquez', 'Colombia'),
            ('Jane', 'Austen', 'Reino Unido'),
            ('Haruki', 'Murakami', 'Japón'),
            ('Isabel', 'Allende', 'Chile'),
            ('Mark', 'Twain', 'Estados Unidos'),
            ('J.K.', 'Rowling', 'Reino Unido');

        -- 2. Insertar datos para editoriales
        INSERT INTO editorial (editorial, pais, ruc, direccion, correo, telefono, contrasenia) VALUES
            ('Penguin Random House', 'Estados Unidos', '12345678901', '123 Fiction St.', 'info@penguin.com', '912345678', 'A1@b2'),
            ('Editorial Planeta', 'España', '98765432109', '456 Nonfiction Ave.', 'contacto@planeta.es', '987654321', 'X9#y8'),
            ('HarperCollins', 'Reino Unido', '45678901234', '789 Reading Rd.', 'contact@harpercollins.com', '912987654', 'B3#d7'),
            ('Alfaguara', 'España', '32165498765', '123 Libro St.', 'info@alfaguara.com', '934567890', 'C7@p4');

        -- 3. Insertar datos para libros (debe ir después de editorial)
        INSERT INTO libro (titulo, isbn, precio, editorial_id, stock) VALUES
            ('Cien Años de Soledad', '9780060883287', 19.99, 1, 50),
            ('Orgullo y Prejuicio', '9780141439518', 15.99, 1, 30),
            ('Kafka en la Orilla', '9788490628624', 18.99, 2, 20),
            ('La Casa de los Espíritus', '9780061121222', 22.50, 1, 40),
            ('Las Aventuras de Tom Sawyer', '9780143039551', 13.80, 2, 35),
            ('Harry Potter y la Piedra Filosofal', '9780747532743', 25.99, 2, 60);

        -- Insertar datos para categorías
        -- Insertar categorías
  

        -- 4. Insertar relación de libros con autores
        INSERT INTO libro_detalle (libro_id, autor_id) VALUES
            (1, 1),
            (2, 2),
            (3, 3),
            (4, 1),
            (5, 2),
            (6, 3);

        -- 5. Insertar relación de categorías con libros
        INSERT INTO categoria_detalle (categoria_id, libro_id) VALUES
            (1, 1),
            (1, 2),
            (1, 3),
            (1, 4),
            (2, 5),
            (3, 6);

        -- 6. Insertar datos para clientes
        INSERT INTO cliente (nombre, apellido, telefono, direccion, correo, contrasenia) VALUES
            ('Juan', 'Pérez', '612345678', 'Calle Falsa 123', 'juan.perez@example.com', 'Pas123&'),
            ('Ana', 'López', '687654321', 'Av. Siempre Viva 456', 'ana.lopez@example.com', 'Pas123&'),
            ('Luis', 'González', '634987654', 'Calle Luna 789', 'luis.gonzalez@example.com', 'Pas123&'),
            ('Patricia', 'Martínez', '690123456', 'Calle Sol 101', 'patricia.martinez@example.com', 'Pas123&');

        -- 7. Insertar datos para ventas
        INSERT INTO ventas (fecha, cliente_id) VALUES
            ('2024-11-01', 1),
            ('2024-11-15', 2),
            ('2024-11-05', 3),
            ('2024-11-20', 4);

        -- 8. Insertar datos para detalles de ventas
        INSERT INTO ventas_detalle (cantidad, precio_final, libro_id, ventas_id, tipo_vendedor) VALUES
            (2, 39.98, 1, 1, 'librería'),
            (1, 18.99, 3, 2, 'librería'),
            (3, 67.50, 4, 3, 'librería'),
            (2, 27.60, 5, 4, 'librería');

        -- 9. Insertar datos para vendedores
        INSERT INTO cliente_vendedor (nombre, apellido, telefono, direccion, correo, contrasenia, tipo_vendedor) VALUES
            ('Carlos', 'Ramírez', '612987654', 'Plaza Central 789', 'carlos.ramirez@ventas.com', 'Pas123&', 'particular'),
            ('María', 'Hernández', '669123987', 'Parque Norte 321', 'maria.hernandez@ventas.com', 'Pas123&', 'particular'),
            ('Eduardo', 'Sánchez', '650123987', 'Calle Libertad 202', 'eduardo.sanchez@ventas.com', 'Pas123&', 'particular'),
            ('Luisa', 'Moreno', '622345678', 'Avenida del Mar 500', 'luisa.moreno@ventas.com', 'Pas123&', 'particular');

        -- 10. Insertar datos para libros de vendedores
        INSERT INTO libros_cliente_vendedor (vendedor_id, titulo, isbn, precio, stock) VALUES
            (1, 'El Principito', '9782070612758', 10.99, 10),
            (2, '1984', '9780451524935', 12.99, 15),
            (3, 'Don Quijote de la Mancha', '9788420478241', 18.50, 8),
            (4, 'Cien Años de Soledad', '9780060883287', 19.99, 12);

    SQL;

    $miPDO->exec($scriptInsert);
    echo "Datos insertados correctamente.\n";
} catch (PDOException $e) {
    die("Error al insertar los datos: " . $e->getMessage());
}



