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
  <link rel="stylesheet" href="../css/css_area_vendedor.css">
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
              <a href="#">Área de Vendedor</a>
              <a href="cerrar_sesion.php">Cerrar Sesion</a>
              <?php endif;?>
          <?php else:
              header('location: ../index.php');
              endif;?>
          </div>
        </div>
      </div>
    </nav>
  </header>
  <h1 class="area-vendedor">Área de Vendedor</h1>
  <section class="table-container">
    <h3>Libros disponibles</h3>

      <?php include 'mostrar_libros.php'; ?>
  </section>

  <section class="containerButtom">
    
    <form action="procesar_area_vendedor.php" method="POST">
    <!-- Añadir libro -->
    <h3>Añadir Libro</h3>
    <input type="hidden" name="accion" value="añadir">
    <input type="text" name="nuevo_titulo" placeholder="Título del libro" >
    <input type="text" name="nuevo_isbn" placeholder="ISBN">
    <input type="number" name="nuevo_precio" placeholder="Precio" step="0.01" >
    <input type="number" name="nuevo_stock" placeholder="Stock">
    <input type="submit"  name="nuevo_accion" value="añadir">

    <!-- Actualizar libro -->
    <h3>Actualizar Libro</h3>
  
    <input type="number" name="id_libro" placeholder="ID del libro">
    <input type="text" name="titulo" placeholder="Nuevo título" >
    <input type="number" name="precio" placeholder="Nuevo precio" step="0.01" >
    <input type="number" name="stock" placeholder="Nuevo stock" >
    <input type="submit"  name="accion" value="actualizar">

    <!-- Eliminar libro -->
    <h3>Eliminar Libro</h3>
 
    <input type="number" name="libro_id" placeholder="ID del libro" >
    <input type="submit"  name="accion" value="eliminar">

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