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

// Obtener la fecha y hora actual
$fecha_actual = date('Y-m-d');
$hora_actual = date('H:i:s');

// Obtener datos del usuario
$usuario_id = $_SESSION['usuario_id'];

// Guardar datos al iniciar jornada
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'iniciar_jornada') {
    $hora_inicio = date('H:i:s');

    $stmt = $conn->prepare("INSERT INTO jornadas (usuario_id, fecha, hora_inicio) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $usuario_id, $fecha_actual, $hora_inicio);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Jornada iniciada correctamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al iniciar la jornada."]);
    }
    $stmt->close();
    exit();
}

// Guardar datos al finalizar jornada
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'finalizar_jornada') {
    $hora_fin = date('H:i:s');

    $stmt = $conn->prepare("UPDATE jornadas SET hora_fin = ? WHERE usuario_id = ? AND fecha = ? AND hora_fin IS NULL");
    $stmt->bind_param("sis", $hora_fin, $usuario_id, $fecha_actual);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Jornada finalizada correctamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al finalizar la jornada o no hay jornada activa."]);
    }
    $stmt->close();
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
        <button id="btn_iniciar_jornada">Iniciar Jornada</button>
        <button id="btn_finalizar_jornada" disabled>Finalizar Jornada</button>
        <script>
        document.getElementById('btn_iniciar_jornada').addEventListener('click', function () {
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

        document.getElementById('btn_finalizar_jornada').addEventListener('click', function () {
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
