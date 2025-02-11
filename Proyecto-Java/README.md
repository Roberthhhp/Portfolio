# 📚 Manga Store Management System

Este proyecto es una aplicación en **Java** que permite gestionar un inventario de mangas, incluyendo funcionalidades como agregar, visualizar, actualizar y eliminar mangas de una base de datos **PostgreSQL**. Además, permite exportar e importar datos en archivos de texto.

## 🚀 Características

✅ Agregar mangas con título, autor, precio y stock.  
✅ Ver todos los mangas registrados en la base de datos.  
✅ Actualizar la información de un manga existente.  
✅ Eliminar mangas del inventario.  
✅ Exportar la lista de mangas a un archivo **.txt**.  
✅ Importar mangas desde un archivo **.txt**.  

## 🛠️ Tecnologías utilizadas

- **Java** (versión 8 o superior)  
- **JDBC** para la conexión con la base de datos  
- **PostgreSQL** como gestor de base de datos  
- **Maven** (opcional, para la gestión de dependencias)  


## 📌 Instalación y Ejecución

### 1️⃣ Configurar la base de datos PostgreSQL

1. Crear una base de datos llamada **MangaStore** en PostgreSQL.  
2. Crear la tabla ejecutando la siguiente consulta SQL:  

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





