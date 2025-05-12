<?php

include 'conexion.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo json_encode(['success' => true, 'message' => 'Script ejecutado correctamente.']);
    exit();
}
    // Validar datos
    if (!$latitud || !$longitud) {
        echo json_encode(['success' => false, 'message' => 'Coordenadas inválidas.']);
        exit();
    }

    // Insertar jornada en la base de datos
    $query = "INSERT INTO jornadas (usuario_id, fecha, hora_inicio, latitud, longitud) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issdd", $usuario_id, $fecha, $hora_inicio, $latitud, $longitud);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Jornada iniciada correctamente.', 'hora_inicio' => $hora_inicio]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al iniciar jornada.']);
    }

    $stmt->close();
    $conn->close();
}
?>
