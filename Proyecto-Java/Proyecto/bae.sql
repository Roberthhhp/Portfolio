-- Crear la base de datos
CREATE DATABASE MangaStore;

-- Crear la tabla 'mangas'
CREATE TABLE mangas (
    id SERIAL PRIMARY KEY,              
    title VARCHAR(100) NOT NULL,        
    author VARCHAR(100) NOT NULL,       
    price DECIMAL(10, 2) NOT NULL,      
    stock INT NOT NULL                  
);

-- Insertar datos iniciales 
INSERT INTO mangas (title, author, price, stock) VALUES
('Naruto', 'Masashi Kishimoto', 9.99, 100),
('One Piece', 'Eiichiro Oda', 12.50, 200),
('Attack on Titan', 'Hajime Isayama', 10.00, 150),
('Dragon Ball', 'Akira Toriyama', 11.75, 120),
('My Hero Academia', 'Kohei Horikoshi', 8.99, 80);

