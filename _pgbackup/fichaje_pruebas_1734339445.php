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
$nombre_usuario = $_SESSION['nombre'] ?? 'Usuario Desconocido';

// Guardar datos al iniciar jornada
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'iniciar_jornada') {
    $latitud = $_POST['latitud'] ?? null;
    $longitud = $_POST['longitud'] ?? null;
    $hora_inicio = date('H:i:s');

    if ($latitud && $longitud) {
        $stmt = $conn->prepare("INSERT INTO jornadas (usuario_id, fecha, hora_inicio, latitud, longitud) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issdd", $usuario_id, $fecha_actual, $hora_inicio, $latitud, $longitud);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Jornada iniciada correctamente."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al iniciar la jornada."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Coordenadas no válidas."]);
    }
    exit();
}

// Guardar datos al iniciar pausa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'iniciar_pausa') {
    $pausa_id = $_POST['pausa_id'] ?? null;
    $hora_inicio_pausa = date('H:i:s');

    if ($pausa_id) {
        $stmt = $conn->prepare("INSERT INTO pausas (id, hora_inicio) VALUES (?, ?)");
        $stmt->bind_param("is", $pausa_id, $hora_inicio_pausa);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Pausa iniciada correctamente."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al iniciar la pausa."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Pausa no válida."]);
    }
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
        <title>Fichaje Diario</title>
        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css">
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>#map { height: 300px; margin-top: 20px; } .btn { font-size: 12px; margin-top: 10px; }</style>
    </head>
    <body>
        <div class="container mt-5" style="padding-top: 25px;">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="text-center text-primary" style="margin-top: 20px; margin-bottom: 51px;">Fichaje Diario</h2>
                    <p><strong style="font-size: 10px;">Usuario:</strong> <?php echo htmlspecialchars($nombre_usuario); ?></p>
                    <div class="mb-3">
                        <p style="font-size: 10px;"> <strong>Fecha:</strong> <?php echo $fecha_actual; ?></p>
                        <p><strong style="font-size: 10px;">Hora Actual:</strong> <span id="hora_actual" style="font-size: 10px;"><?php echo $hora_actual; ?></span></p>
                    </div>
                    <div class="mb-3">
                        <p><strong style="font-size: 10px;">Hora de Inicio:</strong> <span id="hora_inicio" style="font-size: 10px;">Pendiente</span></p>
                        <p><strong style="font-size: 10px;">Hora de Fin:</strong> <span id="hora_fin" style="font-size: 10px;">Pendiente</span></p>
                        <p><strong style="font-size: 10px;">Pausas:</strong> <span id="pausas" style="font-size: 10px;">0</span></p>
                    </div>
                    <div class="mt-3" style="font-size: 10px;">
                        <label for="tipo_pausa" class="form-label">Seleccionar tipo de pausa:</label>
                        <select id="tipo_pausa" class="form-control" disabled style="font-size: 10px;">
                            <option value="">Seleccione una pausa</option>
                            <?php foreach ($pausas as $pausa): ?>
                                <option value="<?php echo $pausa['id']; ?>">
                                    <?php echo htmlspecialchars($pausa['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="text-center mt-3">
                        <button id="btn_iniciar_jornada" class="btn btn-primary btn-sm" onclick="iniciarJornada()">Iniciar Jornada</button>
                        <button id="btn_iniciar_pausa" class="btn btn-sm btn-warning" onclick="iniciarPausa()" disabled>Iniciar Pausa</button>
                        <button id="btn_reanudar_pausa" class="btn btn-sm btn-success" onclick="reanudarPausa()" disabled>Reanudar Pausa</button>
                        <button id="btn_finalizar_jornada" class="btn btn-danger btn-sm" onclick="finalizarJornada()" disabled>Finalizar Jornada</button>
                    </div>
                    <div id="map" style="margin-top: 65px;"></div>
                </div>
            </div>
        </div>
        <!-- Leaflet JS -->
        <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Coordenadas iniciales (Málaga por defecto)
            var latitud = 36.7213; // Latitud de Málaga
            var longitud = -4.4214; // Longitud de Málaga
            var contadorPausas = 0;

            // Inicializar mapa en Málaga
            var map = L.map('map').setView([latitud, longitud], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19
            }).addTo(map);

            // Añadir marcador inicial en Málaga
            var marker = L.marker([latitud, longitud]).addTo(map).bindPopup("Ubicación inicial: Málaga").openPopup();

            // Obtener ubicación del usuario
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (location) {
                        latitud = location.coords.latitude;
                        longitud = location.coords.longitude;

                        // Centrar mapa y actualizar marcador
                        map.setView([latitud, longitud], 15);
                        marker.setLatLng([latitud, longitud]).bindPopup("Tu ubicación").openPopup();
                    },
                    function (error) {
                        console.error("Error al obtener la ubicación: ", error);
                        alert("No se pudo obtener tu ubicación. Mostrando ubicación por defecto (Málaga).");
                    }
                );
            } else {
                alert("La geolocalización no está soportada por tu navegador. Mostrando ubicación por defecto (Málaga).");
            }

            // Actualizar hora en tiempo real
            setInterval(() => {
                const ahora = new Date();
                document.getElementById('hora_actual').textContent = ahora.toLocaleTimeString();
            }, 1000);

           // Función: Iniciar jornada
function iniciarJornada() {
    if (!latitud || !longitud) {
        alert('No se pudieron obtener las coordenadas. Intenta nuevamente.');
        return;
    }

    fetch('fichaje.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            accion: 'iniciar_jornada',
            latitud: latitud,
            longitud: longitud,
        }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const horaInicio = new Date().toLocaleTimeString();
                document.getElementById('hora_inicio').textContent = horaInicio;

                // Habilitar botones y select
                document.getElementById('btn_iniciar_jornada').disabled = true;
                document.getElementById('btn_iniciar_pausa').disabled = false;
                document.getElementById('btn_finalizar_jornada').disabled = false;
                document.getElementById('tipo_pausa').disabled = false;

                alert(data.message);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error al iniciar jornada:', error);
            alert('Hubo un error al procesar la solicitud.');
        });
}

// Función: Iniciar pausa
function iniciarPausa() {
    const tipoPausa = document.getElementById('tipo_pausa').value;

    if (!tipoPausa) {
        alert('Por favor, seleccione un tipo de pausa.');
        return;
    }

    fetch('fichaje.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            accion: 'iniciar_pausa',
            pausa_id: tipoPausa,
        }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('btn_iniciar_pausa').disabled = true;
                document.getElementById('btn_reanudar_pausa').disabled = false;

                // Incrementar contador de pausas
                contadorPausas++;
                document.getElementById('pausas').textContent = contadorPausas;

                alert(data.message);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error al iniciar pausa:', error);
            alert('Hubo un error al procesar la solicitud.');
        });
}

// Función: Finalizar jornada
function finalizarJornada() {
    fetch('fichaje.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            accion: 'finalizar_jornada',
        }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const horaFin = new Date().toLocaleTimeString();
                document.getElementById('hora_fin').textContent = horaFin;

                // Desactivar todos los botones
                document.getElementById('btn_finalizar_jornada').disabled = true;
                document.getElementById('btn_iniciar_jornada').disabled = true;
                document.getElementById('btn_iniciar_pausa').disabled = true;
                document.getElementById('btn_reanudar_pausa').disabled = true;
                document.getElementById('tipo_pausa').disabled = true;

                alert(data.message);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error al finalizar jornada:', error);
            alert('Hubo un error al procesar la solicitud.');
        });
}

        </script>
    </body>
</html>
