<?php
// Página de inicio de sesión
session_start();
include 'conexion.php';  // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Consulta parametrizada para evitar inyecciones SQL
    $query = "SELECT * FROM usuarios WHERE usuario = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $usuario, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {   // Si las credenciales son correctas
        $_SESSION['usuario'] = $usuario;  // Guarda el usuario en la sesión
        header('Location: listado.php');  // Redirige a la página principal
    } else {
        echo "<script>alert('Credenciales Erróneas');</script>";  // Alerta si fallan las credenciales
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>
    <form method="POST">
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" id="usuario" required>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password">

        <button type="submit">Iniciar sesión</button>
    </form>
</body>
</html>