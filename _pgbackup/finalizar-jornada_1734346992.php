<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $hora_fin = date('H:i:s');

    // Actualizar hora de fin en la base de datos
    $query = "UPDATE jornadas SET hora_fin = ? WHERE usuario_id = ? AND fecha = ? AND hora_fin IS NULL";
    $fecha = date('Y-m-d');
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sis", $hora_fin, $usuario_id, $fecha);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Jornada finalizada correctamente.', 'hora_fin' => $hora_fin]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al finalizar jornada.']);
    }

    $stmt->close();
    $conn->close();
}
?>
