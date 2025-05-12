<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $pausa_id = $_POST['pausa_id'];

    // Validar datos
    if (!$pausa_id) {
        echo json_encode(['success' => false, 'message' => 'Selecciona un tipo de pausa.']);
        exit();
    }

    // Actualizar pausa en la base de datos
    $query = "UPDATE jornadas SET pausa_id = ? WHERE usuario_id = ? AND fecha = ? AND hora_fin IS NULL";
    $fecha = date('Y-m-d');
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $pausa_id, $usuario_id, $fecha);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Pausa registrada correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al registrar pausa.']);
    }

    $stmt->close();
    $conn->close();
}
?>
