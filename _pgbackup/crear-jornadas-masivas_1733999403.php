<?php
include 'menu.php'; // Opcional, para incluir el menú
include 'conexion.php'; // Archivo de conexión a la base de datos

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Inicializar array de usuarios
$usuarios = [];

// Consulta para obtener los usuarios activos con rol "usuario"
$query = "SELECT idPrimaria AS id, nombre, apellido FROM usuarios WHERE rol = 'usuario' AND estado = 'activo'";
$result = $conn->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
} else {
    die("Error al obtener usuarios: " . $conn->error);
}

// Procesar formulario si se envía por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir datos del formulario
    $usuario_id = $_POST['usuario_id'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    $pausas = $_POST['pausas'];

    // Validar datos obligatorios
    if (!$usuario_id || !$fecha_inicio || !$fecha_fin) {
        die("Error: Todos los campos obligatorios deben estar completos.");
    }

    // Validar fechas
    if (strtotime($fecha_inicio) > strtotime($fecha_fin)) {
        die("Error: La fecha de inicio no puede ser mayor que la fecha de fin.");
    }

    // Insertar jornadas por cada día en el rango de fechas
    $fecha_actual = $fecha_inicio;
    while (strtotime($fecha_actual) <= strtotime($fecha_fin)) {
        $sql = "INSERT INTO jornadas (usuario_id, fecha, hora_inicio, hora_fin, pausas, fecha_inicio, fecha_fin)
                VALUES ('$usuario_id', '$fecha_actual', '$hora_inicio', '$hora_fin', '$pausas', '$fecha_inicio', '$fecha_fin')";

        if (!$conn->query($sql)) {
            die("Error al insertar jornada: " . $conn->error);
        }

        // Avanzar al siguiente día
        $fecha_actual = date('Y-m-d', strtotime($fecha_actual . ' +1 day'));
    }

    echo "Jornadas creadas exitosamente.";
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Crear Jornadas</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- FontAwesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <style>
        .alert { transition: opacity 1s ease-in-out; }
        .form-label, .form-control, select, textarea { font-size: 10px; }
    </style>
    </head>
    <body>
        <div class="container my-5">
            <div class="card shadow">
                <div class="card-body" style="padding-left: 101px; padding-right: 101px;">
                    <div class="d-flex justify-content-end"><a href="listar-jornadas.php"> <button type="button" class="bg-danger-subtle btn btn-light text-secondary" style="font-size: 8px;"><i class="fa-arrow-alt-circle-left far" style="margin-right: 5px;"></i>Volver
                            </button> </a>
                    </div>
                    <h1 class="card-title text-center text-primary" style="margin-top: 15px; margin-bottom: 50px;">Crear Jornadas</h1>
                    <!-- Formulario -->
                    <form action="crear-jornadas-masivas.php" method="POST">
                        <!-- Selección de Usuario -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="usuario_id" class="form-label">Usuario</label>
                                <select id="usuario_id" name="usuario_id" class="form-control" required>
                                    <option value="">Seleccione un usuario</option>
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <option value="<?= htmlspecialchars($usuario['id']) ?>">
                                            <?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <!-- Resto de Campos -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_fin" class="form-label">Fecha Fin</label>
                                <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" required>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="hora_inicio" class="form-label">Hora de Inicio</label>
                                <input type="time" id="hora_inicio" name="hora_inicio" class="form-control" required>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="hora_fin" class="form-label">Hora de Fin</label>
                                <input type="time" id="hora_fin" name="hora_fin" class="form-control" required>
                            </div>
                            <div class="col-12 mt-3">
                                <label for="pausas" class="form-label">Pausa</label>
                                <select id="pausas" name="pausas" class="form-control" required>
                                    <option value="">Seleccione una pausa</option>
                                    <option value="Desayuno">Desayuno</option>
                                    <option value="Comida">Comida</option>
                                    <option value="Merienda">Merienda</option>
                                    <option value="Café">Café</option>
                                    <option value="Descanso">Descanso</option>
                                    <option value="Otros">Otros</option>
                                </select>
                            </div>
                        </div>
                        <!-- Botones -->
                        <div class="text-center" style="margin-top: 50px; margin-bottom: 50px;">
                            <button type="submit" class="btn btn-primary" style="font-size: 10px;">Crear Jornadas</button>
                            <button type="reset" class="btn btn-secondary" style="font-size: 10px;">Limpiar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
