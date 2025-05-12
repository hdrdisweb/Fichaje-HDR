<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>
<?php
include 'menu.php';
include 'config.php';
?>
<!-- Mensaje de si se borró o no un usuario --> 
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Consulta para obtener los usuarios con rol "usuario" y estado "activo"
$query = "SELECT id, nombre, apellido FROM usuarios WHERE rol = 'usuario' AND estado = 'activo'";
$result = $conexion->query($query);

// Verificar si hay resultados
$usuarios = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}

// Procesar formulario si se envía por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir datos del formulario
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    $pausas = $_POST['pausas'];
    $usuario_id = $_POST['usuario_id'];

    // Validar datos
    if (!$fecha_inicio || !$usuario_id) {
        die("Fecha de inicio y usuario son obligatorios.");
    }

    // Validaciones adicionales
    if (strtotime($fecha_inicio) > strtotime($fecha_fin)) {
        die("La fecha de inicio no puede ser mayor que la fecha de fin.");
    }

    // Si no se proporciona fecha fin, usar fecha inicio para jornada única
    if (!$fecha_fin) {
        $fecha_fin = $fecha_inicio;
    }

    // Generar jornadas para cada día del rango
    $fecha_actual = $fecha_inicio;
    while (strtotime($fecha_actual) <= strtotime($fecha_fin)) {
        $sql = "INSERT INTO jornadas (usuario_id, fecha, hora_inicio, hora_fin, pausas, fecha_inicio, fecha_fin)
                VALUES ('$usuario_id', '$fecha_actual', '$hora_inicio', '$hora_fin', '$pausas', '$fecha_inicio', '$fecha_fin')";
        if (!$conexion->query($sql)) {
            die("Error al crear la jornada: " . $conexion->error);
        }
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
        <style>.alert { transition: opacity 1s ease-in-out; } .form-label, .form-control, select, textarea { font-size: 10px; }</style>
    </head>
    <body>
        <div class="container my-5">
            <div class="card shadow">
                <div class="card-body" style="padding-left: 101px; padding-right: 101px;">
                    <div class="d-flex justify-content-end"> <a href="listar-jornadas.php"><button type="button" class="bg-danger-subtle btn btn-light text-secondary" style="font-size: 8px;"><i class="fa-arrow-alt-circle-left far" style="margin-right: 5px;"></i>Volver
                            </button></a> 
                    </div>
                    <h1 class="card-title text-center text-primary" style="margin-top: 15px; margin-bottom: 50px;">Crear Jornadas</h1>
                    <!-- Mensajes de éxito o error -->
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success" id="successMessage">
                            <?php echo $success; ?>
                        </div>
                    <?php elseif (!empty($error)): ?>
                        <div class="alert alert-danger" id="errorMessage">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <!-- Formulario -->
                    <form action="crear-jornadas-masivas.php" method="POST">
                        <!-- Selección de Usuario en la primera línea -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="usuario_id" class="form-label">Usuario</label>
                                <select id="usuario_id" name="usuario_id" class="form-control" required>
                                    <option value="">Seleccione un usuario</option>
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <option value="<?= $usuario['id'] ?>">
                                            <?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <!-- Resto de campos en la segunda línea -->
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
        <script>
        // Ocultar mensajes de éxito y error después de 5 segundos
        setTimeout(() => {
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');
            if (successMessage) successMessage.style.display = 'none';
            if (errorMessage) errorMessage.style.display = 'none';
        }, 5000);
    </script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    </body>
</html>