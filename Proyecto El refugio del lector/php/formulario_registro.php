<?php
session_start();

// Determinar el tipo de registro
$tipoRegistro = '';
if (isset($_POST['registro'])  && $_POST['registro'] == 'cliente') {
    $tipoRegistro = "cliente";
} else {
    $tipoRegistro = "vendedor";
}
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
    <link rel="stylesheet" href="../css/css_registrarme.css">
    <link rel="stylesheet" href="../css/cart.css">
</head>
<body>
<header>
    <nav>
      <div class="logo">
        <a href="#">El Refugio del Lector</a>
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
        
    <form action="verificacion_registro.php" method="POST">

      <h1>Crea tu cuenta</h1>

      <label for="" >Nombre</label>
      <input type="text" name="name">

      <label for="">Apellidos</label>
      <input type="text" name="lastName">

      <label for="">Teléfono</label>
      <input type="text" name="telefono">

      <label for="">Dirección</label>
      <input type="text" name="direccion">

      <label for="">Email</label>
      <input type="email" name="email" placeholder="example@hotmail.com">

      <label for="">Contraseña</label>
      <input type="password" name="password">

      <label for="">Confirnar Contraseña</label>
      <input type="password" name="verify">

      <label for="">
          <input type="checkbox" name="policies">
          He leído y acepto la Política de Privacidad
      </label>

      <!-- Campo oculto para determinar el tipo de registro -->
      <input type="hidden" name="tipoRegistro" value="<?= htmlspecialchars($tipoRegistro) ?>">

      <input type="submit" value="Registrarme">
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