<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}

include '../conexion.php';
include '../include/funciones.php';

if (!$conn) {
    die('Error: No se pudo conectar a la base de datos.');
}

// Verificar si se recibe un ID de producto
if (!isset($_GET['id'])) {
    die('Error: Falta el ID del producto.');
}

$idProducto = intval($_GET['id']);
$producto = obtenerProducto($idProducto);

if (!$producto) {
    die('Error: El producto no existe.');
}

$esAdmin = ($_SESSION['usuario'] === 'root');

// Obtener lista de usuarios que han valorado el producto si es root
$valoraciones = [];
if ($esAdmin) {
    $stmt = $conn->prepare("SELECT idUs, cantidad FROM votos WHERE idPr = ?");
    $stmt->bind_param("i", $idProducto);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $valoraciones[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../styles.css">
    <title>Detalle del Producto</title>
</head>
<body>
    <h1>Detalle del Producto</h1>
    <p><strong>ID:</strong> <?= $producto['id'] ?></p>
    <p><strong>Nombre:</strong> <?= htmlspecialchars($producto['nombre']) ?></p>

    <?php if ($esAdmin && !empty($valoraciones)): ?>
        <h2>Usuarios que han valorado este producto</h2>
        <table>
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Valoración</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($valoraciones as $valoracion): ?>
                    <tr>
                        <td><?= htmlspecialchars($valoracion['idUs']) ?></td>
                        <td><?= $valoracion['cantidad'] ?> estrellas</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="../listado.php">Volver al listado</a>
</body>
</html>
