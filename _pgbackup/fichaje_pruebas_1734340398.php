<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'conexion.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'usuario') {
    header("Location: login.php?error=unauthorized");
    exit();
}

// Procesar solicitudes AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json'); // Forzar JSON solo para AJAX

    // Obtener la fecha y hora actual
    $fecha_actual = date('Y-m-d');
    $usuario_id = $_SESSION['usuario_id'];

    // Iniciar jornada
   // Procesar solicitudes AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json'); // Forzar JSON solo para AJAX

    // Leer y decodificar el cuerpo JSON de la solicitud
    $input = json_decode(file_get_contents('php://input'), true);

    // Verificar si los datos JSON se recibieron correctamente
    if (!isset($input['accion'])) {
        echo json_encode(["success" => false, "message" => "No se recibió ninguna acción válida."]);
        exit();
    }

    // Obtener la fecha y hora actual
    $fecha_actual = date('Y-m-d');
    $usuario_id = $_SESSION['usuario_id'];

    // Iniciar jornada
    if ($input['accion'] === 'iniciar_jornada') {
        $hora_inicio = date('H:i:s');
        $stmt = $conn->prepare("INSERT INTO jornadas (usuario_id, fecha, hora_inicio) VALUES (?, ?, ?)");
        if (!$stmt) {
            echo json_encode(["success" => false, "message" => "Error al preparar la consulta: " . $conn->error]);
            exit();
        }
        $stmt->bind_param("iss", $usuario_id, $fecha_actual, $hora_inicio);
        echo $stmt->execute()
            ? json_encode(["success" => true, "message" => "Jornada iniciada correctamente."])
            : json_encode(["success" => false, "message" => "Error al iniciar la jornada: " . $stmt->error]);
        $stmt->close();
        exit();
    }

    // Finalizar jornada
    if ($input['accion'] === 'finalizar_jornada') {
        $hora_fin = date('H:i:s');
        $stmt = $conn->prepare("UPDATE jornadas SET hora_fin = ? WHERE usuario_id = ? AND fecha = ? AND hora_fin IS NULL");
        if (!$stmt) {
            echo json_encode(["success" => false, "message" => "Error al preparar la consulta: " . $conn->error]);
            exit();
        }
        $stmt->bind_param("sis", $hora_fin, $usuario_id, $fecha_actual);
        echo ($stmt->execute() && $stmt->affected_rows > 0)
            ? json_encode(["success" => true, "message" => "Jornada finalizada correctamente."])
            : json_encode(["success" => false, "message" => "Error al finalizar la jornada o no hay jornada activa."]);
        $stmt->close();
        exit();
    }

    // Solicitud no reconocida
    echo json_encode(["success" => false, "message" => "Solicitud no válida."]);
    exit();
}
?>
    <!DOCTYPE html>
    <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Pruebas de Fichaje</title>
        </head>
        <body>
            <h1>Pruebas de Fichaje</h1>
            <form id="form_iniciar_jornada">
                <button id="btn_iniciar_jornada" name="iniciar_jornada">Iniciar Jornada</button>
            </form>
            <form id="form_finalizar_jornada">
                <button id="btn_finalizar_jornada" name="finalizar_jornada" disabled>Finalizar Jornada</button>
            </form>
            <script>
        document.getElementById('btn_iniciar_jornada').addEventListener('click', function (event) {
            event.preventDefault();
            fetch('fichaje_pruebas.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ accion: 'iniciar_jornada' })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    document.getElementById('btn_iniciar_jornada').disabled = true;
                    document.getElementById('btn_finalizar_jornada').disabled = false;
                }
            })
            .catch(error => console.error('Error:', error));
        });

        document.getElementById('btn_finalizar_jornada').addEventListener('click', function (event) {
            event.preventDefault();
            fetch('fichaje_pruebas.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ accion: 'finalizar_jornada' })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    document.getElementById('btn_iniciar_jornada').disabled = false;
                    document.getElementById('btn_finalizar_jornada').disabled = true;
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
        </body>
    </html>
