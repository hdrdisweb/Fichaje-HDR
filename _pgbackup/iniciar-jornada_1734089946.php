<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $fecha_inicio = date('Y-m-d'); // Usamos fecha_inicio en lugar de fecha
    $hora_inicio = date('H:i:s');
    $latitud = $_POST['latitud'];
    $longitud = $_POST['longitud'];

    // Validar datos
    if (!$latitud || !$longitud) {
        echo json_encode(['success' => false, 'message' => 'Coordenadas inválidas.']);
        exit();
    }

    // Insertar jornada en la base de datos
    $query = "INSERT INTO jornadas (usuario_id, fecha_inicio, hora_inicio, latitud, longitud) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issdd", $usuario_id, $fecha_inicio, $hora_inicio, $latitud, $longitud);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Jornada iniciada correctamente.', 'hora_inicio' => $hora_inicio]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al iniciar jornada.']);
    }

    $stmt->close();
    $conn->close();
}
?>
