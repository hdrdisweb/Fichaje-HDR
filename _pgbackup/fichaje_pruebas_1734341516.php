<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'conexion.php';

header('Content-Type: application/json');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'usuario') {
    error_log("Error: Usuario no autenticado");
    echo json_encode(["success" => false, "message" => "Usuario no autenticado."]);
    exit();
}

// Verificar la conexión a la base de datos
if (!$conn) {
    error_log("Error: Fallo en la conexión a la base de datos: " . mysqli_connect_error());
    echo json_encode(["success" => false, "message" => "Fallo en la conexión a la base de datos."]);
    exit();
}

// Procesar solicitudes AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leer y decodificar el cuerpo JSON de la solicitud
    $input = json_decode(file_get_contents('php://input'), true);

    // Depuración: Mostrar el cuerpo recibido
    error_log("Cuerpo recibido: " . file_get_contents('php://input'));

    // Validar entrada JSON
    if (!$input) {
        error_log("Error: JSON no válido");
        echo json_encode(["success" => false, "message" => "Datos JSON no válidos."]);
        exit();
    }

    // Verificar acción recibida
    if (!isset($input['accion'])) {
        error_log("Error: Acción no recibida - JSON recibido: " . json_encode($input));
        echo json_encode(["success" => false, "message" => "No se recibió ninguna acción."]);
        exit();
    }

    $fecha_actual = date('Y-m-d');
    $usuario_id = $_SESSION['usuario_id'];

    // Iniciar jornada
    if ($input['accion'] === 'iniciar_jornada') {
        $hora_inicio = date('H:i:s');
        $stmt = $conn->prepare("INSERT INTO jornadas (usuario_id, fecha, hora_inicio) VALUES (?, ?, ?)");

        if (!$stmt) {
            error_log("Error: Fallo al preparar la consulta - " . $conn->error);
            echo json_encode(["success" => false, "message" => "Error al preparar la consulta."]);
            exit();
        }
        $stmt->bind_param("iss", $usuario_id, $fecha_actual, $hora_inicio);
        
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Jornada iniciada correctamente."]);
        } else {
            error_log("Error: " . $stmt->error);
            echo json_encode(["success" => false, "message" => "Error al iniciar la jornada."]);
        }
        $stmt->close();
        exit();
    }

    // Finalizar jornada
    if ($input['accion'] === 'finalizar_jornada') {
        $hora_fin = date('H:i:s');
        $stmt = $conn->prepare("UPDATE jornadas SET hora_fin = ? WHERE usuario_id = ? AND fecha = ? AND hora_fin IS NULL");

        if (!$stmt) {
            error_log("Error: Fallo al preparar la consulta - " . $conn->error);
            echo json_encode(["success" => false, "message" => "Error al preparar la consulta."]);
            exit();
        }
        $stmt->bind_param("sis", $hora_fin, $usuario_id, $fecha_actual);
        
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            echo json_encode(["success" => true, "message" => "Jornada finalizada correctamente."]);
        } else {
            error_log("Error: No se pudo actualizar la jornada - " . $stmt->error);
            echo json_encode(["success" => false, "message" => "Error al finalizar la jornada o no hay jornada activa."]);
        }
        $stmt->close();
        exit();
    }

    // Acción no válida
    error_log("Error: Acción no válida - " . $input['accion']);
    echo json_encode(["success" => false, "message" => "Acción no válida."]);
    exit();
}

// Respuesta general si no se hace una solicitud POST
echo json_encode(["success" => false, "message" => "Solicitud no válida."]);
exit();
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
            <button id="btn_iniciar_jornada" name="btn_iniciar_jornada" type="submit">Iniciar Jornada</button>
        </form>
        <form id="form_finalizar_jornada">
            <button id="btn_finalizar_jornada" name="btn_finalizar_jornada" type="submit" disabled>Finalizar Jornada</button>
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
