<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'conexion.php';

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(['error' => 'Error de conexión a la base de datos']));
}

// Verificar si se recibió la información
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true); // Obtener datos JSON

    // Extraer datos
    $usuario_id = $_SESSION['usuario_id'] ?? null; // Desde la sesión
    $latitud = $data['latitud'] ?? null;
    $longitud = $data['longitud'] ?? null;
    $hora_inicio = $data['hora_inicio'] ?? null;
    $hora_fin = $data['hora_fin'] ?? null;
    $fecha_actual = date('Y-m-d');

    // Log de depuración: Mostrar los datos recibidos
    file_put_contents('log_jornada.txt', print_r([
        'usuario_id' => $usuario_id,
        'latitud' => $latitud,
        'longitud' => $longitud,
        'hora_inicio' => $hora_inicio,
        'hora_fin' => $hora_fin,
        'fecha_actual' => $fecha_actual
    ], true));

    // Validar datos obligatorios
    if (!$usuario_id || !$latitud || !$longitud || !$hora_inicio) {
        echo json_encode(['error' => 'Faltan datos obligatorios']);
        exit;
    }

    // Insertar jornada en la base de datos
    $query = "INSERT INTO jornadas (usuario_id, fecha, hora_inicio, hora_fin, latitud, longitud) 
              VALUES ('$usuario_id', '$fecha_actual', '$hora_inicio', '$hora_fin', '$latitud', '$longitud')";

    if ($conn->query($query)) {
        echo json_encode(['success' => 'Jornada registrada correctamente']);
    } else {
        echo json_encode(['error' => 'Error al registrar la jornada: ' . $conn->error]);
    }
} else {
    echo json_encode(['error' => 'Método no permitido']);
}
?>
