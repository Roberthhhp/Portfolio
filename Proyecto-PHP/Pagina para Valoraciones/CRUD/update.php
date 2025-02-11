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

$idProducto = intval($_GET['id'] ?? 0);
$tipo = $_GET['tipo'] ?? 'producto'; // Detecta si es edición de voto o producto
$usuario = $_SESSION['usuario'];
$esAdmin = ($usuario === 'root');

if ($tipo === 'producto' && $esAdmin) {
    // Editar producto (solo root)
    if (!$idProducto) {
        die('Error: Falta el ID del producto.');
    }
    $producto = obtenerProducto($idProducto);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nuevoNombre = $_POST['nombre'] ?? '';

        if (!empty($nuevoNombre)) {
            $stmt = $conn->prepare("UPDATE productos SET nombre = ? WHERE id = ?");
            $stmt->bind_param("si", $nuevoNombre, $idProducto);
            $stmt->execute();

            header("Location: ../listado.php");
            exit;
        } else {
            echo "Error: El nombre del producto no puede estar vacío.";
        }
    }
} else {
    // Editar voto (usuarios normales y root)
    if (!$idProducto) {
        die('Error: Falta el ID del producto para editar el voto.');
    }

    $stmt = $conn->prepare("SELECT cantidad FROM votos WHERE idPr = ? AND idUs = ?");
    $stmt->bind_param("is", $idProducto, $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $voto = $result->fetch_assoc();

    if (!$voto) {
        die('Error: No tienes un voto registrado para este producto.');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nuevoVoto = intval($_POST['voto'] ?? 0);

        if ($nuevoVoto >= 1 && $nuevoVoto <= 5) {
            $stmt = $conn->prepare("UPDATE votos SET cantidad = ? WHERE idPr = ? AND idUs = ?");
            $stmt->bind_param("iis", $nuevoVoto, $idProducto, $usuario);
            $stmt->execute();

            header("Location: ../listado.php");
            exit;
        } else {
            echo "Error: La valoración debe estar entre 1 y 5.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../styles.css">
    <title><?php echo $tipo === 'producto' ? 'Editar Producto' : 'Editar Voto'; ?></title>
</head>
<body>
    <h1><?php echo $tipo === 'producto' ? 'Editar Producto' : 'Editar Voto'; ?></h1>
    <form method="POST">
        <?php if ($tipo === 'producto'): ?>
            <label>Nombre del Producto:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
        <?php else: ?>
            <label>Tu Valoración:</label>
            <select name="voto" required>
                <option value="1" <?= $voto['cantidad'] == 1 ? 'selected' : '' ?>>1</option>
                <option value="2" <?= $voto['cantidad'] == 2 ? 'selected' : '' ?>>2</option>
                <option value="3" <?= $voto['cantidad'] == 3 ? 'selected' : '' ?>>3</option>
                <option value="4" <?= $voto['cantidad'] == 4 ? 'selected' : '' ?>>4</option>
                <option value="5" <?= $voto['cantidad'] == 5 ? 'selected' : '' ?>>5</option>
            </select>
        <?php endif; ?>
        <button type="submit">Actualizar</button>
    </form>
    <a href="../listado.php">Volver al listado</a>
</body>
</html>
