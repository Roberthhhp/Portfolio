<?php
session_name("sesion_de_usuario");
session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'vendedor') {
    header('Location: ../index.php');
    exit();
}

//------Funciones de validaciones ---------
$errores = [];



require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    echo "$accion";

    switch ($accion) {
        case 'añadir':
       
    // Verificar si se han recibido los datos necesarios
    $titulo = $_POST['nuevo_titulo'] ?? NULL;
    $isbn = $_POST['nuevo_isbn'] ?? NULL;
    $precio = $_POST['nuevo_precio'] ?? NULL;
    $stock = $_POST['nuevo_stock'] ?? NULL;

    // Validar los datos recibidos
    if (empty($titulo) || empty($isbn) || empty($precio) || empty($stock)) {
        $errores[] = "Todos los campos son obligatorios.";
    } elseif ($precio <= 0) {
        $errores[] = "El precio debe ser mayor que 0.";
    } elseif ($stock < 0) {
        $errores[] = "El stock no puede ser negativo.";
    }

    // Verificar si hay errores antes de proceder
    if (!empty($errores)) {
        foreach ($errores as $error) {
            echo "<p>Error: $error</p>";
        }
        exit;
    }

    // Obtener el ID del vendedor desde la sesión
    $vendedor_id = $_SESSION['id_vendedor'];
    //Comenzar la transaction:
    $miPDO->beginTransaction();


    // Consulta SQL para insertar el nuevo libro
    $query = "INSERT INTO libros_cliente_vendedor_copia (vendedor_id, titulo, isbn, precio, stock) 
              VALUES (:vendedor_id, :titulo, :isbn, :precio, :stock)";

    try {
        // Preparar la consulta
        $stmt = $miPDO->prepare($query);

        // Ejecutar la consulta con los parámetros
        $stmt->execute([
            ':vendedor_id' => $vendedor_id,
            ':titulo' => $titulo,
            ':isbn' => $isbn,
            ':precio' => $precio,
            ':stock' => $stock
        ]);

// Operaciones de inserción o actualización
$miPDO->commit();

        // Mensaje de éxito y redirección
        echo "El libro se ha añadido correctamente.";
        header("Refresh: 4; url=area_vendedor.php");
        exit;
    } catch (PDOException $e) {
        echo "Error al añadir el libro: " . $e->getMessage();
    }
    break;


        case 'actualizar':
         
            $libro_id = $_POST['id_libro'] ?? NULL; // Corregido el espacio extra
            $nuevoTitulo = $_POST['titulo'] ?? NULL;
            $nuevoPrecio = $_POST['precio'] ?? NULL;
            $nuevoStock = $_POST['stock'] ?? NULL;
            
             
            
            // Verificar que el ID del libro es obligatorio
        
            
            // Construir partes dinámicas de la consulta
            $updates = [];
            $params = [];
            
            if (!empty($nuevoTitulo)) {
                $updates[] = "titulo = :titulo";
                $params[':titulo'] = $nuevoTitulo;
            } elseif ($nuevoTitulo === '') { // Verifica si el campo está vacío explícitamente
                $errores[] = "El título no puede estar vacío.";
            }
            
            if (!empty($nuevoPrecio) && $nuevoPrecio > 0) {
                $updates[] = "precio = :precio";
                $params[':precio'] = $nuevoPrecio;
            } elseif (!empty($nuevoPrecio) && $nuevoPrecio <= 0) {
                $errores[] = "El precio debe ser mayor a 0.";
            }
            
            if (!empty($nuevoStock) && $nuevoStock >= 0) { // Incluye el stock igual a 0 como válido
                $updates[] = "stock = :stock";
                $params[':stock'] = $nuevoStock;
            } elseif (!empty($nuevoStock) && $nuevoStock < 0) {
                $errores[] = "El stock no puede ser negativo.";
            }
            
            // Verificar errores
            if (!empty($errores)) {
                foreach ($errores as $error) {
                    echo "<p>Error: $error</p>";
                }
                exit;
            }
            
            // Verificar que hay campos para actualizar
            if (empty($updates)) {
                echo "No hay datos para actualizar.";
                exit;
            }
            
            // Construir la consulta SQL
            $query = "UPDATE libros_cliente_vendedor_copia 
                      SET " . implode(', ', $updates) . " 
                      WHERE libro_vendedor_id = :libro_id";
            
            $params[':libro_id'] = $libro_id;
            
            // Ejecutar la consulta
            try {
                $stmt = $miPDO->prepare($query);
                $stmt->execute($params);
                $miPDO->beginTransaction();
// Operaciones de inserción o actualización
$miPDO->commit();

            
                echo "Los datos del libro se han actualizado correctamente.";
                header("Refresh: 4; url=area_vendedor.php");
                exit;
            } catch (PDOException $e) {
                echo "Error al actualizar: " . $e->getMessage();
            }
        break;
            
        case 'eliminar':
            if (!empty($_POST['libro_id'])) {
                $libro_id = $_POST['libro_id'];

                try {
                    $sql = "DELETE FROM libros_cliente_vendedor_copia WHERE libro_vendedor_id = :libro_id";
                    $stmt = $miPDO->prepare($sql);
                    $stmt->execute([':libro_id' => $libro_id]);
                    $miPDO->beginTransaction();
// Operaciones de inserción o actualización
$miPDO->commit();

                    
                    echo "Libro eliminado correctamente.";
                    header("Refresh: 3, area_vendedor.php");
                    exit; // Finalizar el script
                } catch (PDOException $e) {
                    echo "Error al eliminar el libro: " . $e->getMessage();
                }
            } else {
                echo "El ID del libro es obligatorio--.";
                echo "$accion";
            }
            break;

        default:
            echo "Acción no válida.";
    }
}

foreach($errores as $error){
    echo "$error";
}
?>