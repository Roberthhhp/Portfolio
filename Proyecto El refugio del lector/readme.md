
---

El Refugio del Lector

El Refugio del Lector es una plataforma web para la búsqueda, compra y gestión de libros físicos y digitales. Los usuarios pueden explorar un catálogo, buscar libros, gestionar sus cuentas y realizar compras.

Tecnologías utilizadas

PHP: Manejo de sesiones y lógica del servidor.

HTML y CSS: Estructura y estilos de la interfaz de usuario.

JavaScript: Interactividad en la página.

MySQL (posible): Para la gestión de datos (requiere verificación).


Estructura del proyecto

/Proyecto El refugio del lector
│── index.php                # Página principal
│── /css                     # Archivos de estilos CSS
│── /php                     # Lógica del servidor en PHP
│── /js                      # Funcionalidad en JavaScript
│── /img                     # Imágenes del sitio
│── .vscode/settings.json     # Configuración para VS Code

Funcionalidades principales

1. Inicio de sesión y registro:

Manejo de sesiones con session_start().

Diferenciación entre usuarios y vendedores.



2. Búsqueda y navegación de libros:

Barra de búsqueda en index.php.

Enlace a php/libros.php para ver la lista de libros.



3. Carrito de compras:

Sistema de carrito con cart.css para el diseño.



4. Gestión de libros (posiblemente para vendedores):

Archivos CSS como css_area_vendedor.css sugieren un panel de control.




Instalación y configuración

1. Clonar o descargar el proyecto

git clone https://github.com/roberthhhp/Portafolio.git


2. Configurar un servidor local

Se recomienda usar XAMPP o WAMP.

Colocar los archivos en la carpeta htdocs.



3. Base de datos (si aplica)

Si el proyecto usa MySQL, importar el archivo .sql en phpMyAdmin.



4. Ejecutar el proyecto

Acceder desde un navegador con:

http://localhost/proyecto-refugio/index.php



---

