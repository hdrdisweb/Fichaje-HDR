<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'conexion.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No se recibieron datos o el formato es incorrecto.']);
    exit();
}

$usuario_id = $_SESSION['usuario_id'] ?? null;
$latitud = $data['latitud'] ?? null;
$longitud = $data['longitud'] ?? null;
$hora_inicio = $data['hora_inicio'] ?? null;
$fecha_actual = date('Y-m-d');

if (!$usuario_id || !$latitud || !$longitud || !$hora_inicio) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
    exit();
}

$query = "INSERT INTO jornadas (usuario_id, fecha, hora_inicio, latitud, longitud) 
          VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('issdd', $usuario_id, $fecha_actual, $hora_inicio, $latitud, $longitud);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Jornada iniciada correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar la jornada: ' . $stmt->error]);
}
