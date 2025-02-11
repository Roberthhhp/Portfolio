<?php 
session_name("sesion_de_usuario");
session_start();
$tipo_usuario = isset ($_SESSION['tipo_usuario'])  ? $_SESSION['tipo_usuario'] : '';
$nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : ''; // Aseguramos que exista 'nombre'
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libros - El Refugio del Lector</title>
    <link rel="stylesheet" href="../css/css_libros.css">
    <link rel="stylesheet" href="../css/cart.css">
</head>
<body>
<header>
    <nav>
      <div class="logo">
        <a href="../index.php">El Refugio del Lector</a>
      </div>
      <div class="search-bar">
        <input type="text" placeholder="Buscar libros...">
        <button type="button">🔍</button>
      </div>
      <div class="nav-links">
        <ul>
          <li><a href="../index.php">Inicio</a></li>
          <li><a href="libros.php">Libros</a></li>
          <li><a href="#">eBooks</a></li>
          <li><a href="#">Papelería</a></li>
          <li><a href="#">Novedades</a></li>
          <li><a href="#">Ofertas</a></li>
        </ul>
      </div>
      <div class="user-icons">
        <button type="button" id="view-cart">🛒</button>
        <div class="dropdown">
          <button type="button" class="dropdown-button">👤</button>
          <div class="dropdown-content">
          <?php if (!empty($tipo_usuario)): ?>
            <?php if ($tipo_usuario === 'cliente'):?>
              <a href="#">Mis Pedidos</a>
              <a href="cerrar_sesion.php">Cerrar Sesion</a>
            <?php else: ?>
              <a href="#">Mis Pedidos</a>
              <a href="area_vendedor.php">Área de Vendedor</a>
              <a href="cerrar_sesion.php">Cerrar Sesion</a>
              <?php endif;?>
          <?php else:?>
              <a href="inicio_sesion.php">Iniciar Sesión</a>
              <a href="tipo_registro.php">Registrarme</a>
              <a href="inicio_sesion.php">Mis Pedidos</a>
              <a href="inicio_sesion.php">Área de Vendedor</a>
            <?php endif;?>
          </div>
        </div>
      </div>
    </nav>
  </header>
<main>
    <h1>Nuestros Libros</h1>
    <div class="book-container">
        <div class="book">
            <img src="imgs/libro1.jpg" alt="Libro 1">
            <div class="book-title">Título del Libro 1</div>
            <div class="book-price">$19.99</div>
            <button class="add-to-cart">Agregar al Carrito</button>
        </div>
        <div class="book">
            <img src="imgs/libro2.jpg" alt="Libro 2">
            <div class="book-title">Título del Libro 2</div>
            <div class="book-price">$25.50</div>
            <button class="add-to-cart">Agregar al Carrito</button>
        </div>
        <div class="book">
            <img src="imgs/libro3.jpg" alt="Libro 3">
            <div class="book-title">Título del Libro 3</div>
            <div class="book-price">$15.75</div>
            <button class="add-to-cart">Agregar al Carrito</button>
        </div>
        <div class="book">
            <img src="imgs/libro4.jpg" alt="Libro 4">
            <div class="book-title">Título del Libro 4</div>
            <div class="book-price">$22.00</div>
            <button class="add-to-cart">Agregar al Carrito</button>
        </div>
        <div class="book">
            <img src="imgs/libro5.jpg" alt="Libro 5">
            <div class="book-title">Título del Libro 5</div>
            <div class="book-price">$18.99</div>
            <button class="add-to-cart">Agregar al Carrito</button>
        </div>
        <div class="book">
            <img src="imgs/libro6.jpg" alt="Libro 6">
            <div class="book-title">Título del Libro 6</div>
            <div class="book-price">$20.00</div>
            <button class="add-to-cart">Agregar al Carrito</button>
        </div>
    </div>

    <div class="pagination">
        <a href="#">« Anterior</a>
        <a href="#">1</a>
        <a href="#">2</a>
        <a href="#">3</a>
        <a href="#">Siguiente »</a>
    </div>
</main>

<footer>
    <div class="footer-content">
        <p>&copy; 2024 El Refugio del Lector. Todos los derechos reservados.</p>
        <div class="social-icons">
            <a href="#">Facebook</a>
            <a href="#">Twitter</a>
            <a href="#">Instagram</a>
        </div>
    </div>
</footer>

<!-- Modal del carrito -->
<div class="modal" id="cartModal" style="display:none;"> <!-- Establecer display: none inicialmente -->
    <div class="modal-content">
        <h2>Carrito de Compras</h2>
        <ul class="cart-items" id="cartItems"></ul>
        <button class="delete-cart" id="clearCart">Vaciar carrito</button>
        <button class="close-btn" id="closeModal">Cerrar</button>
    </div>
</div>

<script src="../js/cart.js"></script>

</body>
</html>


