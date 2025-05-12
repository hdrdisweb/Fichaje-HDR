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

// Obtener listado de pausas desde la base de datos
$query_pausas = "SELECT id, nombre FROM pausas";
$result_pausas = $conn->query($query_pausas);
$pausas = [];
if ($result_pausas && $result_pausas->num_rows > 0) {
    while ($row = $result_pausas->fetch_assoc()) {
        $pausas[] = $row;
    }
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
            var latitud = null;
            var longitud = null;
            var contadorPausas = 0;

            // Inicializar mapa
            var map = L.map('map').setView([36.7213, -4.4214], 13); // Málaga por defecto
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19
            }).addTo(map);

            // Obtener ubicación del usuario
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (location) {
                        latitud = location.coords.latitude;
                        longitud = location.coords.longitude;
                        map.setView([latitud, longitud], 15);
                        L.marker([latitud, longitud]).addTo(map).bindPopup("Tu ubicación").openPopup();
                    },
                    function () {
                        alert("No se pudo obtener tu ubicación. Mostrando ubicación por defecto (Málaga).")
                    }
                );
            } else {
                alert("La geolocalización no está soportada por tu navegador.");
            }

            function iniciarJornada() {
                if (!latitud || !longitud) {
                    alert('No se pudieron obtener las coordenadas. Intenta nuevamente.');
                    return;
                }
                const data = {
                    usuario_id: <?php echo $usuario_id; ?>,
                    latitud: latitud,
                    longitud: longitud,
                    hora_inicio: new Date().toLocaleTimeString()
                };
                fetch('guardar_jornada.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        document.getElementById('hora_inicio').textContent = data.hora_inicio;
                        alert(result.message);
                        document.getElementById('btn_iniciar_jornada').disabled = true;
                        document.getElementById('btn_iniciar_pausa').disabled = false;
                        document.getElementById('btn_finalizar_jornada').disabled = false;
                        document.getElementById('tipo_pausa').disabled = false;
                    } else {
                        alert(result.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }

            function iniciarPausa() {
                const tipoPausa = document.getElementById('tipo_pausa').value;
                if (!tipoPausa) {
                    alert('Por favor, seleccione un tipo de pausa.');
                    return;
                }
                const data = {
                    usuario_id: <?php echo $usuario_id; ?>,
                    pausa_id: tipoPausa,
                    hora_inicio: new Date().toLocaleTimeString()
                };
                fetch('guardar_pausa.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        contadorPausas++;
                        document.getElementById('pausas').textContent = contadorPausas;
                        alert(result.message);
                        document.getElementById('btn_iniciar_pausa').disabled = true;
                        document.getElementById('btn_reanudar_pausa').disabled = false;
                    } else {
                        alert(result.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }

            function finalizarJornada() {
                const data = {
                    usuario_id: <?php echo $usuario_id; ?>,
                    hora_fin: new Date().toLocaleTimeString()
                };
                fetch('finalizar_jornada.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        document.getElementById('hora_fin').textContent = data.hora_fin;
                        alert(result.message);
                        document.getElementById('btn_finalizar_jornada').disabled = true;
                        document.getElementById('btn_iniciar_pausa').disabled = true;
                        document.getElementById('btn_reanudar_pausa').disabled = true;
                        document.getElementById('tipo_pausa').disabled = true;
                    } else {
                        alert(result.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        </script>
    </body>
</html>
