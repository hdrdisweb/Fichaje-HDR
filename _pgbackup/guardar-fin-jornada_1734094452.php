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
$hora_fin = $data['hora_fin'] ?? null;
$fecha_actual = date('Y-m-d');

if (!$usuario_id || !$hora_fin) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
    exit();
}

$query = "UPDATE jornadas 
          SET hora_fin = ? 
          WHERE usuario_id = ? AND fecha = ? AND hora_fin IS NULL";
$stmt = $conn->prepare($query);
$stmt->bind_param('sis', $hora_fin, $usuario_id, $fecha_actual);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Jornada finalizada correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al finalizar la jornada: ' . $stmt->error]);
}
