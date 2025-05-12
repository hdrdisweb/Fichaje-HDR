<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'menu.php';
include 'conexion.php';

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Inicializar variables
$success = "";
$error = "";

// Obtener ID de la actividad desde la URL
$actividad_id = $_GET['id'] ?? null;

if (!$actividad_id) {
    die("Error: ID de la actividad no especificado.");
}

// Consultar los datos de la actividad existente
$query_actividad = "SELECT * FROM actividades WHERE id = '$actividad_id'";
$result_actividad = $conn->query($query_actividad);

if ($result_actividad->num_rows == 0) {
    die("Error: Actividad no encontrada.");
}

$actividad = $result_actividad->fetch_assoc();

// Obtener lista de usuarios activos
$query_usuarios = "SELECT idPrimaria AS id, nombre, apellido FROM usuarios WHERE estado = 'activo'";
$result_usuarios = $conn->query($query_usuarios);
if (!$result_usuarios) {
    die("Error al obtener usuarios: " . $conn->error);
}

// Procesar formulario si se envía por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_POST['usuario_id'];
    $descripcion = $_POST['descripcion'];
    $horas_programadas = $_POST['horas_programadas'];
    $fecha = $_POST['fecha'];

    // Validar datos obligatorios
    if (!$usuario_id || !$descripcion || !$fecha) {
        $error = "Todos los campos obligatorios deben estar completos.";
    } else {
        // Actualizar actividad
        $sql = "UPDATE actividades 
                SET usuario_id = '$usuario_id', descripcion = '$descripcion', 
                    horas_programadas = '$horas_programadas', fecha = '$fecha' 
                WHERE id = '$actividad_id'";

        if ($conn->query($sql)) {
            $success = "Actividad actualizada exitosamente.";
        } else {
            $error = "Error al actualizar actividad: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Editar Actividad</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- FontAwesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <style>.alert { transition: opacity 1s ease-in-out; } .form-label, .form-control { font-size: 10px; }</style>
    </head>
    <body>
        <div class="container my-5">
            <div class="card shadow">
                <div class="card-body" style="padding-left: 101px; padding-right: 101px;">
                    <div class="d-flex justify-content-end"><a href="listar-actividades.php"> <button type="button" class="bg-danger-subtle btn btn-light text-secondary" style="font-size: 8px;"><i class="fa-arrow-alt-circle-left far" style="margin-right: 5px;"></i>Volver
                            </button> </a>
                    </div>
                    <h1 class="card-title text-center text-primary" style="margin-top: 15px; margin-bottom: 50px;">Editar Actividad</h1>
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
                    <form action="" method="POST">
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="usuario_id" class="form-label">Usuario</label>
                                <select id="usuario_id" name="usuario_id" class="form-control" required>
                                    <option value="">Seleccione un usuario</option>
                                    <?php while ($usuario = $result_usuarios->fetch_assoc()): ?>
                                        <option value="<?= htmlspecialchars($usuario['id']) ?>" <?= $actividad['usuario_id'] == $usuario['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea id="descripcion" name="descripcion" class="form-control" rows="3" required><?= htmlspecialchars($actividad['descripcion']) ?></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="horas_programadas" class="form-label">Horas Programadas</label>
                                <input type="number" id="horas_programadas" name="horas_programadas" class="form-control" step="0.01" value="<?= htmlspecialchars($actividad['horas_programadas']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="fecha" class="form-label">Fecha</label>
                                <input type="date" id="fecha" name="fecha" class="form-control" required value="<?= htmlspecialchars($actividad['fecha']) ?>">
                            </div>
                        </div>
                        <div class="text-center" style="margin-top: 50px; margin-bottom: 50px;">
                            <button type="submit" class="btn btn-primary" style="font-size: 10px;">Actualizar Actividad</button>
                            <button type="reset" class="btn btn-secondary" style="font-size: 10px;">Limpiar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
        setTimeout(() => {
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');
            if (successMessage) {
                successMessage.style.display = 'none';
                window.location.href = 'listar-actividades.php';
            }
            if (errorMessage) {
                errorMessage.style.display = 'none';
            }
        }, 5000);
        </script>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
<?php include 'footer.php'; ?>
