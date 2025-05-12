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
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>#map { height: 300px; margin-top: 20px; } .btn { font-size: 12px; margin-top: 10px; } .small-text { font-size: 10px; }</style>
    </head>
    <body>
        <div class="container mt-5" style="padding-top: 25px;">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="text-center text-primary" style="margin-top: 20px; margin-bottom: 51px;">Fichaje Diario</h2>
                    <p><strong style="font-size: 10px;">Usuario:</strong> <?php echo htmlspecialchars($nombre_usuario); ?></p>
                    <div class="mb-3">
                        <p style="font-size: 10px;"><strong>Fecha:</strong> <?php echo $fecha_actual; ?></p>
                        <p><strong style="font-size: 10px;">Hora Actual:</strong> <span id="hora_actual"><?php echo $hora_actual; ?></span></p>
                    </div>
                    <div class="mb-3">
                        <p><strong style="font-size: 10px;">Hora de Inicio:</strong> <span id="hora_inicio">Pendiente</span></p>
                        <p><strong style="font-size: 10px;">Hora de Fin:</strong> <span id="hora_fin">Pendiente</span></p>
                        <p><strong style="font-size: 10px;">Pausas:</strong> <span id="pausas">0</span></p>
                    </div>
                    <div class="mt-3">
                        <label for="tipo_pausa" class="form-label small-text">Seleccionar tipo de pausa:</label>
                        <select id="tipo_pausa" class="form-control small-text" disabled>
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
                        <button id="btn_iniciar_pausa" class="btn btn-warning btn-sm" onclick="iniciarPausa()" disabled>Iniciar Pausa</button>
                        <button id="btn_reanudar_pausa" class="btn btn-success btn-sm" onclick="reanudarPausa()" disabled>Reanudar Pausa</button>
                        <button id="btn_finalizar_jornada" class="btn btn-danger btn-sm" onclick="finalizarJornada()" disabled>Finalizar Jornada</button>
                    </div>
                    <div id="map" style="margin-top: 65px;"></div>
                </div>
            </div>
        </div>
        <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            let latitud = 36.7213;
            let longitud = -4.4214;

            const map = L.map('map').setView([latitud, longitud], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

            const marker = L.marker([latitud, longitud]).addTo(map).bindPopup("Ubicación inicial: Málaga").openPopup();

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    location => {
                        latitud = location.coords.latitude;
                        longitud = location.coords.longitude;
                        map.setView([latitud, longitud], 15);
                        marker.setLatLng([latitud, longitud]).bindPopup("Tu ubicación").openPopup();
                    },
                    () => alert("No se pudo obtener tu ubicación.")
                );
            }

            setInterval(() => {
                const ahora = new Date();
                document.getElementById('hora_actual').textContent = ahora.toLocaleTimeString();
            }, 1000);
        </script>
    </body>
</html>
