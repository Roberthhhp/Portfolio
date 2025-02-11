<?php
// Archivo: include/funciones.php
// Funciones auxiliares para manejar productos y valoraciones
function obtenerProducto($idProducto) {
    global $conn;
    $query = "SELECT * FROM productos WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $idProducto);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Genera la visualización de estrellas según la valoración promedio de un producto
function pintarEstrellas($idProducto) {
    global $conn;

     // Obtiene el promedio de votos y la cantidad total de valoraciones
    $query = "SELECT AVG(cantidad) AS promedio, COUNT(*) AS total FROM votos WHERE idPr = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $idProducto);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    $promedio = round($result['promedio'], 1);
    $total = $result['total'];

    // Si no hay valoraciones, retorna un mensaje
    if ($total == 0) {
        return "<div>0 valoraciones</div>";
    }

    $estrellas = '';
    $entero = floor($promedio);  // Parte entera del promedio
    $decimal = $promedio - $entero;  // Parte decimal del promedio

    // Agrega estrellas completas según la parte entera
    for ($i = 0; $i < $entero; $i++) {
        $estrellas .= '<i class="fa-solid fa-star" style="color: gold;"></i> ';
    }

    // Si hay una fracción mayor o igual a 0.5, agrega una estrella a la mitad
    if ($decimal >= 0.5) {
        $estrellas .= '<i class="fa-solid fa-star-half" style="color: gold;"></i> ';
    }

    // Completa con estrellas vacías hasta llegar a 5
    while (strlen($estrellas) / 49 < 5) {
        $estrellas .= '<i class="fa-regular fa-star" style="color: gold;"></i> ';
    }

    return "<div>{$estrellas} ({$total} valoraciones)</div>";
}
?>
