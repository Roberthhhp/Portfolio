<?php 
session_name("sesion_de_usuario");
session_start();
$tipo_usuario = isset ($_SESSION['tipo_usuario'])  ? $_SESSION['tipo_usuario'] : '';
$nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : ''; // Aseguramos que exista 'nombre'
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>El Refugio del Lector</title>
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="css/cart.css">
 
</head>
<body>
  <header>
    <nav>
      <div class="logo">
        <a href="index.php">El Refugio del Lector</a>
      </div>
      <div class="search-bar">
        <input type="text" placeholder="Buscar libros...">
        <button type="button">🔍</button>
      </div>
      <div class="nav-container">

  <div class="nav-links">
    <ul>
      <li><a href="index.php">Inicio</a></li>
      <li><a href="php/libros.php">Libros</a></li>
      <li><a href="#">eBooks</a></li>
      <li><a href="#">Papelería</a></li>
      <li><a href="#">Novedades</a></li>
      <li><a href="#">Ofertas</a></li>
    </ul>
  </div>
</div>

      <div class="user-icons">
        <button type="button" id="view-cart">🛒</button>
        <div class="dropdown">
          <button type="button" class="dropdown-button">👤</button>
          <div class="dropdown-content">
          <?php if (!empty($tipo_usuario)): ?>
            <?php if ($tipo_usuario === 'cliente'):?>
              <a href="#">Mis Pedidos</a>
              <a href="php/cerrar_sesion.php">Cerrar Sesion</a>
            <?php else: ?>
              <a href="#">Mis Pedidos</a>
              <a href="php/area_vendedor.php">Área de Vendedor</a>
              <a href="php/cerrar_sesion.php">Cerrar Sesion</a>
              <?php endif;?>
          <?php else:?>
              <a href="php/inicio_sesion.php">Iniciar Sesión</a>
              <a href="php/tipo_registro.php">Registrarme</a>
              <a href="php/inicio_sesion.php">Mis Pedidos</a>
              <a href="php/inicio_sesion.php">Área de Vendedor</a>
            <?php endif;?>
          </div>
        </div>
      </div>
    </nav>
  </header>
  <section class="portada">
    <img src="images/portada.jpg" alt="">
    <?php if (!empty($nombre_usuario)): ?>
      <h1>Bienvenido <?php echo $nombre_usuario?></h1>
    <?php else: ?>
      <h1>Bienvenido a nuestra Librería Online</h1>
    <?php endif; ?>
    <a href="">Conocer más</a>
  </section>
  <main class="book-section">
  
        <!-- Don Quijote -->
        <div class="book-card">
            <img src="https://static.cegal.es/imagenes/marcadas/9788419/978841908700.gif" style="width: 100%; border-radius: 8px;">
            <h2>Don Quijote de la Mancha</h2>
            <p>Por Miguel de Cervantes</p>
            <button>Ver más</button>
        </div>

        <!-- Otros libros -->
        <div class="book-card">
            <img src="https://upload.wikimedia.org/wikipedia/commons/7/7a/The_Great_Gatsby_Cover_1925_Retouched.jpg" alt="Portada de El Gran Gatsby" style="width: 100%; border-radius: 8px;">
            <h2>El Gran Gatsby</h2>
            <p>Por F. Scott Fitzgerald</p>
            <button>Ver más</button>
        </div>
        <div class="book-card">
            <img src="https://cdn.prod.website-files.com/6034d7d1f3e0f52c50b2adee/625454132a4288889ad4b1d8_6034d7d1f3e0f57d87b2b2a9_Moby-dick-herman-melville-editorial-alma.jpeg" alt="Portada de Moby Dick" style="width: 100%; border-radius: 8px;">
            <h2>Moby Dick</h2>
            <p>Por Herman Melville</p>
            <button>Ver más</button>
        </div>
        <div class="book-card">
            <img src="https://cdn.prod.website-files.com/6034d7d1f3e0f52c50b2adee/6254291caac6d1e42e8986df_62023ceb41cd1c2807b2841a_9788418933011.jpeg" alt="Portada de 1984" style="width: 100%; border-radius: 8px;">
            <h2>1984</h2>
            <p>Por George Orwell</p>
            <button>Ver más</button>
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
  <div class="modal" id="cartModal">
    <div class="modal-content">
      <h2>Carrito de Compras</h2>
      <ul class="cart-items" id="cartItems"></ul>
      <button class="delete-cart" id="clearCart">Vaciar carrito</button>
      <button class="close-btn" id="closeModal">Cerrar</button>
    </div>
  </div>
  <script src="js/cart.js"></script>
 
</body>
</html>