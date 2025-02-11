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

$idProducto = intval($_GET['id'] ?? 0);
$tipo = $_GET['tipo'] ?? 'producto'; // Detectar si es eliminación de producto o voto
$usuario = $_SESSION['usuario'];

if ($tipo === 'producto') {
    // Solo el administrador puede borrar productos
    if ($usuario !== 'root') {
        die('Error: No tienes permisos para borrar productos.');
    }

    $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param("i", $idProducto);
    $stmt->execute();

    header("Location: ../listado.php");
    exit;
} else {
    // El usuario normal y el root pueden borrar votos
    $stmt = $conn->prepare("DELETE FROM votos WHERE idPr = ? AND idUs = ?");
    $stmt->bind_param("is", $idProducto, $usuario);
    $stmt->execute();

    header("Location: ../listado.php");
    exit;
}
?>
