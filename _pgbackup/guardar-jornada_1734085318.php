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
    $usuario_id = $_SESSION['usuario_id'] ?? null; // Obtener el usuario desde la sesión
    $latitud = $_POST['latitud'] ?? null;
    $longitud = $_POST['longitud'] ?? null;
    $hora_inicio = $_POST['hora_inicio'] ?? null;
    $hora_fin = $_POST['hora_fin'] ?? null;
    $fecha_actual = date('Y-m-d');

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
