<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'conexion.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado.']);
    exit();
}

// Depuración: Confirmar entrada al script
echo "Inicio del script<br>";

// Capturar datos del POST
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Datos no recibidos.']);
    exit();
}

// Asignar variables
$usuario_id = $_SESSION['usuario_id'];
$fecha_inicio = date('Y-m-d');
$hora_inicio = date('H:i:s');
$latitud = $data['latitud'] ?? null;
$longitud = $data['longitud'] ?? null;

// Validar datos
if (!$latitud || !$longitud) {
    echo json_encode(['success' => false, 'message' => 'Coordenadas inválidas.']);
    exit();
}

// Depuración: Mostrar datos antes de la consulta
echo "Datos recibidos:<br>";
echo "Usuario ID: $usuario_id<br>";
echo "Fecha Inicio: $fecha_inicio<br>";
echo "Hora Inicio: $hora_inicio<br>";
echo "Latitud: $latitud<br>";
echo "Longitud: $longitud<br>";

// Insertar jornada en la base de datos
$query = "INSERT INTO jornadas (usuario_id, fecha_inicio, hora_inicio, latitud, longitud) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $conn->error]);
    exit();
}

$stmt->bind_param("issdd", $usuario_id, $fecha_inicio, $hora_inicio, $latitud, $longitud);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Jornada iniciada correctamente.', 'hora_inicio' => $hora_inicio]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al ejecutar la consulta: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
