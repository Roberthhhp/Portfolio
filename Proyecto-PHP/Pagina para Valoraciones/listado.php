<?php
// Verifica si el usuario ha iniciado sesión; si no, lo redirige al login
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

include 'conexion.php';
include 'include/funciones.php';

// Verifica que la función necesaria está definida en funciones.php
if (!function_exists('pintarEstrellas')) {
    die('Error: La función pintarEstrellas no está definida en funciones.php.');
}

// Confirma que la conexión a la base de datos fue exitosa
if (!$conn) {
    die('Error: No se pudo conectar a la base de datos.');
}

// Procesar solicitudes POST enviadas con fetch
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $idProducto = $data['idProducto'];
    $voto = $data['voto'];
    $usuario = $_SESSION['usuario'];

    // Verifica si el usuario ya ha valorado el producto
    $query = "SELECT * FROM votos WHERE idPr = ? AND idUs = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('is', $idProducto, $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["error" => "Ya has valorado este producto"]);
    } else {
        // Inserta la nueva valoración en la base de datos
        $insert = "INSERT INTO votos (cantidad, idPr, idUs) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert);
        $stmt->bind_param('iis', $voto, $idProducto, $usuario);
        $stmt->execute();

        // Devuelve la nueva valoración en formato JSON
        echo json_encode(["success" => pintarEstrellas($idProducto)]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Listado de Productos</title>
    <script>
        // Envía la votación de un producto al servidor usando fetch
        async function enviarVoto(idProducto, voto) {
            const response = await fetch('listado.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ idProducto, voto })
            });
            const data = await response.json();
            console.log(data); // Depuración

            if (data.error) {
                alert(data.error);
            } else {
                document.getElementById(`valoracion-${idProducto}`).innerHTML = data.success;
            }
        }
    </script>
</head>
<body>
    <h1>Bienvenido, <?php echo $_SESSION['usuario']; ?></h1>
    <a href="logout.php">Cerrar sesión</a>

    <?php if ($_SESSION['usuario'] === 'root'): ?>
        <a href="CRUD/crear.php">Crear Producto</a>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Valoración</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Obtiene la lista de productos de la base de datos
            $query = "SELECT * FROM productos";
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()) {
                $idProducto = $row['id'];
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['nombre']}</td>";
                echo "<td id='valoracion-$idProducto'>" . pintarEstrellas($idProducto) . "</td>";
                echo "<td>";
                echo "<select onchange=\"enviarVoto($idProducto, this.value)\">";
                echo "<option value=''>Seleccionar</option>";
                echo "<option value='1'>1</option>";
                echo "<option value='2'>2</option>";
                echo "<option value='3'>3</option>";
                echo "<option value='4'>4</option>";
                echo "<option value='5'>5</option>";
                echo "</select>";
                echo "<a href='CRUD/detalle.php?id=$idProducto'>Detalle</a>";
                
                // Opciones de administración para el usuario root
                if ($_SESSION['usuario'] === 'root') {
                    echo "<a href='CRUD/update.php?id=$idProducto'>Editar Producto</a>";
                    echo "<a href='CRUD/update.php?id=$idProducto&tipo=voto'>Editar Voto</a>";
                    echo "<a href='CRUD/borrar.php?id=$idProducto'>Borrar Producto</a>";
                    echo "<a href='CRUD/borrar.php?id=$idProducto&tipo=voto'>Borrar Voto</a>";
                } else {

                    // Opciones para usuarios regulares
                    echo "<a href='CRUD/update.php?id=$idProducto&tipo=voto'>Editar Voto</a>";
                    echo "<a href='CRUD/borrar.php?id=$idProducto&tipo=voto'>Eliminar Voto</a>";
                }
                
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
