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

// Obtener usuarios activos
$query_usuarios = "SELECT idPrimaria AS id, nombre, apellido 
                   FROM usuarios 
                   WHERE estado = 'activo' AND rol IN ('jefe de equipo', 'usuario')";
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
        // Insertar actividad
        $sql = "INSERT INTO actividades (usuario_id, descripcion, horas_programadas, fecha)
                VALUES ('$usuario_id', '$descripcion', '$horas_programadas', '$fecha')";

        if ($conn->query($sql)) {
            $success = "Actividad creada exitosamente.";
        } else {
            $error = "Error al crear actividad: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Crear Actividad</title>
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
                    <div class="d-flex justify-content-end">
                        <a href="listar-actividades.php"> <button type="button" class="bg-danger-subtle btn btn-light text-secondary" style="font-size: 8px;">
                                <i class="fa-arrow-alt-circle-left far" style="margin-right: 5px;"></i>Volver
                            </button> </a>
                    </div>
                    <h1 class="card-title text-center text-muted" style="margin-top: 15px; margin-bottom: 50px;">Crear Actividad</h1>
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
                                <select id="usuario_id" name="usuario_id" class="form-control" required style="font-size: 10px;">
                                    <option value="">Seleccione un usuario</option>
                                    <?php while ($usuario = $result_usuarios->fetch_assoc()): ?>
                                        <option value="<?= htmlspecialchars($usuario['id']) ?>">
                                            <?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea id="descripcion" name="descripcion" class="form-control" rows="3" required style="font-size: 10px;"></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="horas_programadas" class="form-label">Horas Programadas</label>
                                <input type="number" id="horas_programadas" name="horas_programadas" class="form-control" step="0.01" style="font-size: 10px;">
                            </div>
                            <div class="col-md-6">
                                <label for="fecha" class="form-label">Fecha</label>
                                <input type="date" id="fecha" name="fecha" class="form-control" required style="font-size: 10px;">
                            </div>
                        </div>
                        <div class="text-center" style="margin-top: 50px; margin-bottom: 50px;">
                            <button type="submit" class="btn btn-secondary" style="font-size: 10px;">Crear Actividad</button>
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
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
<?php include 'footer.php'; ?>
