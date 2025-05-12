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
$pausa_id = $data['pausa_id'] ?? null;
$fecha_actual = date('Y-m-d');
$hora_actual = date('H:i:s');

if (!$usuario_id || !$pausa_id) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
    exit();
}

$query = "INSERT INTO jornadas (usuario_id, fecha, pausa_id, hora_inicio) 
          VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('isss', $usuario_id, $fecha_actual, $pausa_id, $hora_actual);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Pausa registrada correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar la pausa: ' . $stmt->error]);
}
