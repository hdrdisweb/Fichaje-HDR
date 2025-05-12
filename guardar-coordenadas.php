<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'conexion.php';

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(['error' => 'Error de conexión a la base de datos']));
}

// Verificar si se recibió la información
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_POST['usuario_id'] ?? null;
    $latitud = $_POST['latitud'] ?? null;
    $longitud = $_POST['longitud'] ?? null;
    $hora_inicio = $_POST['hora_inicio'] ?? null;

    // Validar datos
    if (!$usuario_id || !$latitud || !$longitud || !$hora_inicio) {
        echo json_encode(['error' => 'Todos los campos son obligatorios']);
        exit;
    }

    // Fecha actual
    $fecha_actual = date('Y-m-d');

    // Insertar jornada
    $query = "INSERT INTO jornadas (usuario_id, fecha, hora_inicio, latitud, longitud) 
              VALUES ('$usuario_id', '$fecha_actual', '$hora_inicio', '$latitud', '$longitud')";

    if ($conn->query($query)) {
        echo json_encode(['success' => 'Jornada iniciada correctamente']);
    } else {
        echo json_encode(['error' => 'Error al iniciar jornada: ' . $conn->error]);
    }
} else {
    echo json_encode(['error' => 'Método no permitido']);
}
?>