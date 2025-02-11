<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}

include '../conexion.php';

if (!$conn) {
    die('Error: No se pudo conectar a la base de datos.');
}

// Verificar si el usuario es administrador
if ($_SESSION['usuario'] !== 'root') {
    die('Error: No tienes permisos para crear productos.');
}

// Si se envía el formulario, crear el producto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreProducto = $_POST['nombre'] ?? '';

    if (!empty($nombreProducto)) {
        $stmt = $conn->prepare("INSERT INTO productos (nombre) VALUES (?)");
        $stmt->bind_param("s", $nombreProducto);
        $stmt->execute();

        header("Location: ../listado.php");
        exit;
    } else {
        echo "Error: El nombre del producto no puede estar vacío.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../styles.css">
    <title>Crear Producto</title>
</head>
<body>
    <h1>Crear Producto</h1>
    <form method="POST">
        <label>Nombre del Producto:</label>
        <input type="text" name="nombre" required>
        <button type="submit">Crear</button>
    </form>
    <a href="../listado.php">Volver al listado</a>
</body>
</html>
