<?php

include_once 'conexion.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {

    // Recogida de la información del formulario
    $errores = [];
    $name = $_POST['name'] ?? '';
    $lastName = $_POST['lastName'] ?? NULL;
    $telephone = $_POST['telefono'] ?? NULL;
    $address = $_POST['direccion'] ?? NULL;
    $email = $_POST['email'] ?? NULL;
    $password = $_POST['password'] ?? NULL;
    $passwordVerify = $_POST['verify'] ?? NULL;
    $policies = isset($_POST['policies']);
    $recordType = $_POST['tipoRegistro'] ?? NULL;
    // elimina espacios en blanco al inicio y al final
    $email = trim($email);
    // convierte un string a minúsculas
    $email = strtolower($email);
    $tipoRegistro=$_POST['tipoRegistro'];
}

//--------------------------------------------------
// FUNCIONES DE VALIDACIÓN
//--------------------------------------------------

function validateText(string $text): bool
{
    $text = trim($text);
    return !empty($text) && preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $text) && strlen($text) <= 25;
}

function validarTelefono(string $number): bool
{
    $number = preg_replace('/[\s\-]/', '', $number);
    return preg_match('/^\d{9,13}$/', $number) && preg_match('/^(6|7|8|9)\d{8}$|^(\+34)?(6|7|8|9)\d{8}$/', $number);
}

function validateEmail(string $text): bool
{
    return filter_var($text, FILTER_VALIDATE_EMAIL);
}

function validatePassword(string $text): bool
{
    return !empty($text) &&
           strlen($text) >= 4 &&
           strlen($text) <= 8 &&
           preg_match('/[a-z]/', $text) &&
           preg_match('/[A-Z]/', $text) &&
           preg_match('/\d/', $text) &&
           preg_match('/[\W_]/', $text);
}

function validateVerification(string $text1, string $text2): bool
{
    return !empty($text1) && !empty($text2) && $text1 === $text2;
}

//------------------------------------------------------
// VALIDACIONES
//------------------------------------------------------

if (!validateText($name)) {
    $errores[] = "El campo nombre es obligatorio, no puede estar vacío y solo puede contener letras.";
}

if (!validateText($lastName)) {
    $errores[] = "El campo apellidos es obligatorio, no puede estar vacío y solo puede contener letras.";
}



if (!validarTelefono($telephone)) {
    $errores[] = "El teléfono debe contener 9 a 13 dígitos y tener un formato válido.";
}

if (!validateEmail($email)) {
    $errores[] = "El email no cuenta con el formato correcto, por favor siga el ejemplo.";
}

if (!validatePassword($password)) {
    $errores[] = "La contraseña debe tener entre 4 y 8 caracteres. Debe incluir al menos un número, una letra minúscula, una letra mayúscula y un carácter especial.";
}

if (!validateVerification($password, $passwordVerify)) {
    $errores[] = "Las contraseñas no coinciden, por favor inténtelo nuevamente.";
}

if (!$policies) {
    $errores[] = "Por favor primero acepte las políticas de privacidad.";
}

if (empty($errores)) {
    if ($tipoRegistro === 'cliente') {
        // Verificar si el cliente ya existe por correo
        $sql = "SELECT * FROM cliente WHERE correo = :email";
        $stmt = $miPDO->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "<br>El cliente con este correo ya existe.</br>";
            header("Refresh: 3, tipo_registro.php");
            exit; // Finalizar el script
        } else {
            // Insertar nuevo cliente
            $sql = "INSERT INTO cliente (nombre, apellido, telefono, direccion, correo, contrasenia) 
                    VALUES (:nombre, :apellido, :telefono, :direccion, :correo, :contrasenia)";
            $stmt = $miPDO->prepare($sql);
            $stmt->bindParam(':nombre', $name);
            $stmt->bindParam(':apellido', $lastName);
            $stmt->bindParam(':telefono', $telephone);
            $stmt->bindParam(':direccion', $address);
            $stmt->bindParam(':correo', $email);
            $stmt->bindParam(':contrasenia', $password);

            try {
                $stmt->execute();
                echo "<p>Cliente agregado exitosamente!</p>";
                  // Iniciar sesión para el cliente
                  session_name("sesion_de_usuario");
                  session_start();
                  $_SESSION['nombre'] = $name;
                  $_SESSION['tipo_usuario'] = 'cliente';
                  header("Location: ../index.php");
                  exit;
  
                exit; // Finalizar el script
            } catch (PDOException $e) {
                echo "<p><br>Error al registrar cliente: </br></p>" . $e->getMessage();
                exit; // Finalizar el script
            }
        }
    } elseif ($tipoRegistro === 'vendedor') {
        // Verificar si el cliente existe por correo
        $sql = "SELECT vendedor_id FROM cliente_vendedor WHERE correo = :email";
        $stmt = $miPDO->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "<br>Correo registrado por otro vendedor.</br>";
            header("Refresh: 4, tipo_registro.php");
            exit; // Finalizar el script
        } else {
            // Insertar nuevo cliente
            $sql = "INSERT INTO cliente_vendedor (nombre, apellido, telefono, direccion, correo, contrasenia) 
                    VALUES (:nombre, :apellido, :telefono, :direccion, :correo, :contrasenia)";
            $stmt = $miPDO->prepare($sql);
            $stmt->bindParam(':nombre', $name);
            $stmt->bindParam(':apellido', $lastName);
            $stmt->bindParam(':telefono', $telephone);
            $stmt->bindParam(':direccion', $address);
            $stmt->bindParam(':correo', $email);
            $stmt->bindParam(':contrasenia', $password);

            try {
                $stmt->execute();
                echo "<p>Vendedor agregado exitosamente!</p>";
                  // Iniciar sesión para el cliente
                  session_name("sesion_de_usuario");
                  session_start();
                  $_SESSION['nombre'] = $name;
                  $_SESSION['tipo_usuario'] = 'vendedor';
                  header("Location: ../index.php");
                  exit;
  
                exit; // Finalizar el script
            } catch (PDOException $e) {
                echo "<p>Error al registrar vendedor: </p>" . $e->getMessage();
                exit; // Finalizar el script
            }
        }
    } else {
        die("Tipo de registro no válido.");
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/css_errores.css">
<title>Document</title>
</head>
<body>

<?php
if(!empty($errores)){
                
    echo "<div class='error-messages'>";
    echo "<ul>";

    foreach($errores as $error):
        echo "<li> $error </li>";
    endforeach;
    header("Refresh:4,tipo_registro.php");
    echo "</ul>";
    echo "</div>";


}


?>

</body>
</html>