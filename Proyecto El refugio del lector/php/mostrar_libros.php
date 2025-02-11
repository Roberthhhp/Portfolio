<?php

// Incluir la conexión
require 'conexion.php';

try {
    // Verificar si $_SESSION['id_vendedor'] está configurado
    if (!isset($_SESSION['id_vendedor'])) {
        throw new Exception("El ID del vendedor no está definido en la sesión.");
    }

    // ID del vendedor a buscar
    $vendedorId = $_SESSION['id_vendedor'];

    // Consulta SQL preparada
    $sql = "SELECT * FROM `libros_cliente_vendedor_copia` WHERE `vendedor_id` = :vendedor_id";
    $stmt = $miPDO->prepare($sql);

    // Asociar el parámetro y ejecutar
    $stmt->bindParam(':vendedor_id', $vendedorId, PDO::PARAM_INT);
    $stmt->execute();

    // Obtener resultados
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Depuración: Verificar el resultado de la consulta
    if (!$resultados) {
        echo "No se encontraron libros para este vendedor.";
        return; // Salir si no hay resultados
    }

    // Mostrar resultados en una tabla HTML
    echo "<table border='1' cellpadding='10' cellspacing='0'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>ID_Libro</th>";
    echo "<th>Título</th>";
    echo "<th>ISBN</th>";
    echo "<th>Precio</th>";
    echo "<th>Stock</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach ($resultados as $libro) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($libro['libro_vendedor_id']) . "</td>";
        echo "<td>" . htmlspecialchars($libro['titulo']) . "</td>";
        echo "<td>" . htmlspecialchars($libro['isbn']) . "</td>";
        echo "<td>$" . htmlspecialchars($libro['precio']) . "</td>";
        echo "<td>" . htmlspecialchars($libro['stock']) . "</td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";

} catch (PDOException $e) {
    // Capturar errores específicos de la base de datos
    echo "Error en la consulta: " . htmlspecialchars($e->getMessage());
} catch (Exception $e) {
    // Capturar errores generales
    echo "Error: " . htmlspecialchars($e->getMessage());
}
?>
