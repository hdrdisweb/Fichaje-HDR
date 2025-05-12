<?php
// Habilitar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir conexión a la base de datos
include 'conexion.php';

// Verificar que se haya recibido una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leer datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    // Verificar que los datos necesarios están presentes
    if (isset($data['usuario_id'], $data['pausa_id'], $data['hora_fin'])) {
        $usuario_id = intval($data['usuario_id']);
        $pausa_id = intval($data['pausa_id']);
        $hora_fin = $data['hora_fin'];

        // Preparar consulta para actualizar la pausa en la tabla `pausas`
        $query = "UPDATE pausas SET hora_fin = ? WHERE usuario_id = ? AND id = ? AND hora_fin IS NULL";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param('sii', $hora_fin, $usuario_id, $pausa_id);

            if ($stmt->execute() && $stmt->affected_rows > 0) {
                // Respuesta exitosa
                echo json_encode(['success' => true, 'message' => 'Pausa finalizada correctamente.']);
            } else {
                // No se encontró una pausa activa para finalizar
                echo json_encode(['success' => false, 'message' => 'No se encontró una pausa activa para finalizar.']);
            }

            $stmt->close();
        } else {
            // Error al preparar la consulta
            echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $conn->error]);
        }
    } else {
        // Datos incompletos
        echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    }
} else {
    // Método no permitido
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}

// Cerrar conexión
$conn->close();
?>
