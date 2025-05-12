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
    if (isset($data['usuario_id'], $data['latitud'], $data['longitud'], $data['hora_inicio'])) {
        $usuario_id = intval($data['usuario_id']);
        $latitud = floatval($data['latitud']);
        $longitud = floatval($data['longitud']);
        $hora_inicio = $data['hora_inicio'];
        $fecha = date('Y-m-d');

        // Preparar consulta para insertar los datos en la tabla `jornadas`
        $query = "INSERT INTO jornadas (usuario_id, fecha, hora_inicio, latitud, longitud) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param('issdd', $usuario_id, $fecha, $hora_inicio, $latitud, $longitud);

            if ($stmt->execute()) {
                // Respuesta exitosa
                echo json_encode(['success' => true, 'message' => 'Jornada iniciada correctamente.']);
            } else {
                // Error al ejecutar la consulta
                echo json_encode(['success' => false, 'message' => 'Error al iniciar la jornada: ' . $stmt->error]);
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
