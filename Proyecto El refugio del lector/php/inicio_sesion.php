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
    <title>Iniciar Sesion</title>
    <link rel="stylesheet" href="../css/css_iniciar_sesion.css">
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
    <section>
        <form action="procesar_inicio_sesion.php" method="post">
            <h1>Iniciar Sesion</h1>
            <div class="inputbox">
                <ion-icon name="mail-outline"></ion-icon>
                <input type="email" name ="email">
                <label for="">Email</label>
            </div>
            <div class="inputbox">
                <ion-icon name="lock-closed-outline"></ion-icon>
                <input type="password" name="password">
                <label for="">Contraseña</label>
            </div>
            <div class="forget">
                <label for=""><input type="checkbox">Recordarme</label>
                <a href="#">Me olvide la contraseña</a>
            </div>
            <button type="submit">Ingresar</button>
            <div class="register">
                <p>No tengo una cuenta <a href="#">Registrarme</a></p>
            </div>
        </form>
    </section>
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
      <script src="../js/cart.js"></script>
    </body>
    </html>
    
</body>
</html>