<?php

// Guardar sesion
session_name("sesion_de_usuario");
session_start();

require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verificar si los datos son nulos o no
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;

    if ($email && $password) {
        try {
            // Primero, intentamos encontrar al usuario en la tabla 'cliente'
            $stmt = $miPDO->prepare('SELECT * FROM cliente WHERE correo = :correo');
            $stmt->bindParam(':correo', $email, PDO::PARAM_STR);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC); // Obtener el usuario

            if ($usuario) {
                // Verificar si la contraseña es correcta
                if ($usuario['contrasenia'] === $password) {
                    // Iniciar sesión de manera segura
                    session_regenerate_id(true); // Previene fijación de sesión
                    $_SESSION['email'] = $usuario['correo'];
                    $_SESSION['nombre'] = $usuario['nombre'];

                    // Establecer que el usuario es un cliente
                    $_SESSION['tipo_usuario'] = 'cliente';

                    // Redirigir al index
                    header('location: ../index.php');
                    exit;
                } else {
                    // Contraseña incorrecta
                    echo "<p><strong>Contraseña incorrecta.</strong></p>";
                }
            } else {
                // Si no es un cliente, verificar si es un cliente vendedor
                $stmt = $miPDO->prepare('SELECT * FROM cliente_vendedor WHERE correo = :correo');
                $stmt->bindParam(':correo', $email, PDO::PARAM_STR);
                $stmt->execute();
                $vendedor = $stmt->fetch(PDO::FETCH_ASSOC); // Obtener el cliente vendedor

                if ($vendedor) {
                    // Verificar si la contraseña es correcta
                    if ($vendedor['contrasenia'] === $password) {
                        // Iniciar sesión de manera segura
                        session_regenerate_id(true); // Previene fijación de sesión
                        $_SESSION['email'] = $vendedor['correo'];
                        $_SESSION['nombre'] = $vendedor['nombre'];
                        // Establecer que el usuario es un cliente vendedor
                        $_SESSION['tipo_usuario'] = 'vendedor';
                        //--------Recuperar ID------------
                        
                        $stmt = $miPDO->prepare("SELECT vendedor_id FROM cliente_vendedor WHERE correo = :correo");
                        $stmt->bindParam(':correo', $email, PDO::PARAM_STR);
                        $stmt->execute();
                        $vendedor_id = $stmt->fetchColumn();

                        if ($vendedor_id) {
                            $_SESSION['id_vendedor'] =  $vendedor_id;
                            echo "El ID del vendedor es: " . $vendedor_id;
                        } else {
                            echo "No se encontró un vendedor con ese correo.";
                        }



                        //----------------------------


                        // Redirigir al index
                        header('location: ../index.php');
                        exit;
                    } else {
                        // Contraseña incorrecta
                        echo "<p><strong>Contraseña incorrecta.</strong></p>";
                    }
                } else {
                    // Usuario no encontrado en ninguna de las tablas
                    echo "<p><strong>El usuario no existe.</strong></p>";
                }
            }
        } catch (PDOException $e) {
            // Manejo seguro de errores
            error_log("Error de conexión: " . $e->getMessage());
            echo "<p>Ocurrió un error. Por favor, inténtalo más tarde.</p>";
        }
    } else {
        echo "<p><strong>Por favor, completa todos los campos.</strong></p>";
    }
} else {
    // Si no se recibieron datos del formulario
    echo "<p>ERROR: No se recibieron datos del formulario.</p>";
}

?>
