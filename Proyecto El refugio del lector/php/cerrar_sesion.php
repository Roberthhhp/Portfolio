<?php 
// Iniciar sesión
session_name("sesion_de_usuario");
session_start();

// Destruir la sesión
session_destroy();

// Redirigir a index
header('location: ../index.php');
exit;


