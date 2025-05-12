<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'conexion.php';

// Depuración: Verifica si se reciben datos
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No se recibieron datos o el formato es incorrecto.']);
    exit();
}

// Capturar datos recibidos
$usuario_id = $_SESSION['usuario_id'];
$latitud = $data['latitud'] ?? null;
$longitud = $data['longitud'] ?? null;
$fecha_inicio = date('Y-m-d');
$hora_inicio = date('H:i:s');

// Validar datos
if (!$latitud || !$longitud) {
    echo json_encode(['success' => false, 'message' => 'Coordenadas inválidas.']);
    exit();
}

// Insertar en la base de datos
$query = "INSERT INTO jornadas (usuario_id, fecha_inicio, hora_inicio, latitud, longitud) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("issdd", $usuario_id, $fecha_inicio, $hora_inicio, $latitud, $longitud);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Jornada iniciada correctamente.', 'hora_inicio' => $hora_inicio]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al iniciar la jornada: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
